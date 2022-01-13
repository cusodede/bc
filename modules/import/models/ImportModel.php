<?php
declare(strict_types = 1);

namespace app\modules\import\models;

use app\components\helpers\ArrayHelper;
use app\components\helpers\TemporaryHelper;
use app\modules\import\helpers\ImportHelper;
use app\modules\import\ImportModule;
use app\modules\import\models\active_record\Import;
use app\modules\import\models\active_record\ImportStatus;
use Exception;
use pozitronik\filestorage\models\FileStorage;
use pozitronik\filestorage\traits\FileStorageTrait;
use pozitronik\sys_exceptions\models\SysExceptions;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * Class ImportModel
 * @property mixed $importFile атрибут загрузки файла
 * @property null|int $domain домен импорта (метка загрузки)
 * @property int $skipRows количество пропускаемых строк от начала
 * @property bool $skipEmptyRows пропускать ли пустые строки
 * @property int $importChunkSize количество импортируемых записей, обрабатываемых за раз
 * @property-read array $mappingRules правила соответствия полей в формате
 * @property-read ?string $filename путь загруженного/обрабатываемого импорта
 * @property-read int $count количество прогруженных строк
 * @property-read int $done количество импортированных строк
 * @property-read int $percent процент импортированных строк
 * @property-read int $errorCount количество строк с ошибкой импорта
 * @property array $errorMessages Массив ошибок, собранных при импорте
 *
 * @property bool $fallbackLoader True для использования phpspreadsheet для загрузки
 *
 * @property-read ImportStatus $importStatus Модель хранения статуса загрузки
 */
class ImportModel extends Model {
	use FileStorageTrait;

	public const STATUS_REGISTERED = 0;//загружено, взято в работу
	public const STATUS_PARSING = 1;//парсинг xls
	public const STATUS_PRELOADING = 2;//предзагрузка в таблицу импорта
	public const STATUS_IMPORTING = 3;//импортируется
	public const STATUS_DONE = 4;//обработано
	public const STATUS_ERROR = 5;//ошибка

	private const LOAD_CHUNK_SIZE = 1000;

	/**
	 * @var bool True для использования phpspreadsheet для загрузки
	 */
	public bool $fallbackLoader = false;

	/**
	 * @var string|null ActiveRecord-модель, в которую производится импорт
	 */
	public ?string $model = null;
	/**
	 * @var mixed $importFile атрибут загрузки файла
	 */
	public mixed $importFile = null;

	/**
	 * @var int|null $domain домен импорта (метка загрузки)
	 */
	public ?int $domain = null;

	/**
	 * @var int $skipRows количество пропускаемых строк от начала
	 */
	public int $skipRows = 0;

	/**
	 * @var bool $skipEmptyRows пропускать ли пустые строки
	 */
	public bool $skipEmptyRows = true;

	/**
	 * @var int $importChunkSize количество импортируемых записей, обрабатываемых за раз
	 */
	public int $importChunkSize = 100;

	/**
	 * @var array Массив ошибок, собранных при импорте
	 */
	public array $errorMessages = [];

	/**
	 * @var string|null $_filename Имя загруженного файла в локальной ФС
	 */
	private ?string $_filename;

	/**
	 * @var int|null $_count количество прогруженных строк
	 */
	private ?int $_count = null;

	/**
	 * @var array|null Правила разбора
	 */
	private ?array $_rules = null;

	/**
	 * @var ImportStatus|null Модель хранения статуса
	 */
	private ?ImportStatus $_importStatus = null;

	/**
	 * @inheritDoc
	 */
	public function rules():array {
		return [
			[['skipRows'], 'integer'],
			[['domain'], 'default', 'value' => time()],
			[['skipEmptyRows'], 'boolean'],
			[['model'], 'string']
		];
	}

	/**
	 * @inheritDoc
	 */
	public function attributeLabels():array {
		return [
			'importFile' => 'Выберите файл для импорта'
		];
	}

	/**
	 * @inheritDoc
	 */
	public function init():void {
		$this->domain = $this->domain??time();//валидатор не валидирует?
	}

	/**
	 * @return ImportStatus
	 */
	public function getImportStatus():ImportStatus {
		return $this->_importStatus ??= ImportStatus::Upsert(['model' => $this->model, 'domain' => $this->domain]);
	}

	/**
	 * @param int $status
	 */
	protected function updateStatus(int $status):void {
		$this->importStatus->status = $status;
		$this->importStatus->save();
	}

	/**
	 * @param string $error
	 */
	protected function updateError(string $error):void {
		$this->importStatus->error = $error;
		$this->updateStatus(self::STATUS_ERROR);
	}

	/**
	 * @param int $processed
	 */
	protected function updateProcessed(int $processed):void {
		$this->importStatus->processed = $processed;
		$this->importStatus->save();
	}

	/**
	 * @param int $skipped
	 */
	protected function updateSkipped(int $skipped):void {
		$this->importStatus->skipped = $skipped;
		$this->importStatus->save();
	}

	/**
	 * @param int $imported
	 */
	protected function updateImported(int $imported):void {
		$this->importStatus->imported = $imported;
		$this->importStatus->save();
	}

	/**
	 * return void
	 */
	protected function updateFilename():void {
		$this->importStatus->filename = $this->getOriginalFileName();
		$this->importStatus->save();
	}

	/**
	 * Импорт во временную таблицу
	 * @return bool
	 * @throws Exception
	 * @throws Throwable
	 */
	public function preload():bool {
		$this->updateStatus(self::STATUS_PARSING);

		try {
			$dataArray = ImportHelper::loadXls($this->filename, false);
		} catch (Throwable $t) {
			$this->updateError('Ошибка при разборе файла: '.$t->getMessage());
			return false;
		}

		$this->updateStatus(self::STATUS_PRELOADING);
		$dataArray = array_slice($dataArray, $this->skipRows);
		if ($this->skipEmptyRows) {
			$dataArray = array_filter($dataArray);//ignore empty rows
		}
		$dataArray = array_map(function($row) {
			return [
				'data' => $this->quote(serialize(array_map("trim", $row))),
				'domain' => $this->domain,
				'model' => $this->model
			];
		}, $dataArray);
		if (null !== $transaction = Yii::$app->db->beginTransaction()) {
			while ([] !== $dataArray) {
				$import = array_splice($dataArray, 0, self::LOAD_CHUNK_SIZE);
				try {
					$this->_count += Yii::$app->db->createCommand()->batchInsert(Import::tableName(), ['data', 'domain', 'model'], $import)->execute();
				} catch (Throwable $t) {
					$transaction->rollBack();
					SysExceptions::log($t);
					$this->updateError('Ошибка предзагрузки'.$t->getMessage());
					return false;
				}
			}
			$transaction->commit();
			$this->updateProcessed($this->_count);
		}
		return true;
	}

	/**
	 * Пытаемся решить проблему: символ \ не экранируется внутри сериализованной строки. Я так и не придумал, как это правильно
	 * сделать, и не понимаю, что тут происходит. Поэтому вынужденно стрипаем \ из загрузок.
	 * Вообще хуйня и todo конечно
	 * @param string $sql
	 * @return string
	 */
	private function quote(string $sql):string {
		return preg_replace("/\\\\/", '-', $sql);
	}

	/**
	 * @param string|resource $value
	 * @return mixed
	 */
	private function unserialize(mixed $value):mixed {
		if (is_resource($value) && 'stream' === get_resource_type($value)) {
			$result = stream_get_contents($value);
			fseek($value, 0);//не забываем перемотать кассету
		} else {
			$result = $value;
		}
		return unserialize($result, ['allowed_classes' => false]);
	}

	/**
	 * @throws Throwable
	 * todo: добавить правило, разрешающее скипать существующие данные
	 */
	public function import():void {
		Yii::info(sprintf("Begin import on domain %s", $this->domain), 'import.main');
		$this->updateStatus(self::STATUS_IMPORTING);
		$importCount = 0;
		$skippedCount = 0;
		/** @var Import[] $data */
		while ([] !== $data = Import::find()->where(['domain' => $this->domain, 'processed' => Import::NOT_PROCESSED])->limit($this->importChunkSize)->all()) {
			Yii::info(sprintf("Processing %s records on domain %s", count($data), $this->domain), 'import.main');
			foreach ($data as $importRecord) {
				$importRow = $this->unserialize($importRecord->data);
				Yii::info(sprintf("importRow count: %s", count($importRow)), 'import.main');
				$mappedColumnData = [];
				$mappedFieldsData = [];
				foreach ($importRow as $columnIndex => $value) {
					$errors = [];
					/** @var array $currentRule */
					if ((null === $currentRule = ArrayHelper::getValue($this->mappingRules, $columnIndex)) || !is_array($currentRule)) continue;

					//есть функция переопределения вставки
					if ((null !== $foreignMatch = ArrayHelper::getValue($currentRule, 'foreign.match')) && is_callable($foreignMatch) && null !== $matchedValue = $foreignMatch($value, $importRow)) {//функция нашла совпадение, вернула значение
						Yii::info(sprintf("matched value: %s on value %s", $matchedValue, $value), 'import.main');
						$value = $matchedValue;
					} elseif (null !== $foreignClass = ArrayHelper::getValue($currentRule, 'foreign.class')) {
						$foreignAttribute = ArrayHelper::getValue($currentRule, 'foreign.attribute', new Exception('Foreign attribute parameter is required'));
						if (null !== $foreignModel = ImportHelper::addInstance($foreignClass, [$foreignAttribute => $value], null, false, $errors)) {
							$value = (null === $return = ArrayHelper::getValue($currentRule, 'foreign.key'))?$foreignModel->primaryKey:$foreignModel->$return;
							Yii::info(sprintf("foreignClass: %s on value %s", $foreignClass, $value), 'import.main');
						} else {
							Yii::info(sprintf("addInstance failed on foreign model %s, errors scope: %s", $foreignClass, TemporaryHelper::Errors2String($errors)), 'import.main');
							$this->rowError($importRecord, $skippedCount, $errors);
							continue;
						}
					}
					/*поля БД и атрибуты модели собираются по разному, чтобы была возможность работать с релейшенами*/
					if (ArrayHelper::getValue($currentRule, 'foreign.relational', false)) {
						if ((null !== $relationMatch = ArrayHelper::getValue($currentRule, 'foreign.relationMatch')) && is_callable($relationMatch)) {
							$value = $relationMatch($value, $importRow);
						}
					} else {
						$mappedColumnData[$currentRule['attribute']] = $value;
					}
					$mappedFieldsData[$currentRule['attribute']] = $value;

				}
				$errors = [];
				null === ImportHelper::addInstance($this->model, $mappedColumnData, $mappedFieldsData, false, $errors)
					?$this->rowError($importRecord, $skippedCount, $errors)
					:$this->rowSuccess($importRecord, $importCount);
			}
		}
		$this->updateStatus(self::STATUS_DONE);
	}

	/**
	 * @param Import $importRecord
	 * @param int $importCount
	 */
	private
	function rowSuccess(Import $importRecord, int &$importCount):void {
		$importRecord->processed = Import::PROCESSED;
		$importRecord->save();
		$importCount++;
		$this->updateImported($importCount);
	}

	/**
	 * @param Import $importRecord
	 * @param int $skippedCount
	 * @param array $errors
	 */
	private
	function rowError(Import $importRecord, int &$skippedCount, array $errors):void {
		$importRecord->processed = Import::PROCESSED_ERROR;
		$importRecord->save();
		$skippedCount++;
		$this->updateSkipped($skippedCount);
		$this->updateError(TemporaryHelper::Errors2String($errors));
		$this->errorMessages[] = $errors;
	}

	/**
	 * Подчищаем обработанные данные
	 */
	public
	function clear():void {
		Import::deleteAll(['model' => $this->model, 'domain' => $this->domain, 'processed' => Import::PROCESSED]);
	}

	/**
	 * @return string|null
	 * @throws Throwable
	 */
	public
	function getFilename():?string {
		if (null !== $lastFileName = ArrayHelper::getValue($this->files(['importFile']), 0)) {
			/** @var FileStorage $lastFileName */
			return $lastFileName->path;
		}
		return null;
	}

	/**
	 * @return string|null
	 * @throws Throwable
	 */
	private
	function getOriginalFileName():?string {
		if (null !== $lastFileName = ArrayHelper::getValue($this->files(['importFile']), 0)) {
			/** @var FileStorage $lastFileName */
			return $lastFileName->name;
		}
		return null;
	}

	/**
	 * @return int
	 */
	public
	function getCount():int {
		if (null === $this->_count) {
			$this->_count = (int)Import::find()->where(['model' => $this->model, 'domain' => $this->domain])->count();
		}
		return $this->_count;
	}

	/**
	 * @return int
	 */
	public
	function getPercent():int {
		return (int)(($this->done / $this->count) * 100);
	}

	/**
	 * @return int
	 */
	public
	function getDone():int {
		return (int)Import::find()->where(['model' => $this->model, 'domain' => $this->domain, 'processed' => [Import::PROCESSED, Import::PROCESSED_ERROR]])->count();
	}

	/**
	 * @return int
	 */
	public
	function getErrorCount():int {
		return (int)Import::find()->where(['model' => $this->model, 'domain' => $this->domain, 'processed' => Import::PROCESSED_ERROR])->count();
	}

	/**
	 * @return array
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public
	function getMappingRules():array {
		return $this->_rules ??= ImportModule::param("mappingRules.{$this->model}", []);
	}

}