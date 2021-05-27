<?php
declare(strict_types = 1);

namespace app\modules\import\models;

use app\models\core\TemporaryHelper;
use app\modules\import\models\active_record\Import;
use Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use pozitronik\filestorage\models\FileStorage;
use pozitronik\filestorage\traits\FileStorageTrait;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Class ImportModel
 * @property mixed $importFile атрибут загрузки файла
 * @property null|int $domain домен импорта (метка загрузки)
 * @property int $skipRows количество пропускаемых строк от начала
 * @property bool $skipEmptyRows пропускать ли пустые строки
 * @property int $importChunkSize количество импортируемых записей, обрабатываемых за раз
 * @property array $mappingRules правила соответствия полей в формате
 * @property-read ?string $filename путь загруженного/обрабатываемого импорта
 */
class ImportModel extends Model {
	use FileStorageTrait;

	/**
	 * @var string|null ActiveRecord-модель, в которую производится импорт
	 */
	public ?string $model = null;
	/**
	 * @var mixed $importFile атрибут загрузки файла
	 */
	public $importFile;
	/**
	 * правила соответствия полей
	 * @var array $mappingRules
	 */
	public array $mappingRules = [];

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
	 * @var string|null $_filename Имя загруженного файла в локальной ФС
	 */
	private ?string $_filename;

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
	public function init():void {
		$this->domain = $this->domain??time();//валидатор не валидирует?
	}

	/**
	 * @return array
	 * @throws Exception
	 * @noinspection BadExceptionsProcessingInspection
	 */
	private function loadXls():array {
		try {
			$reader = new Xlsx();
			$reader->setReadDataOnly(true);
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($this->filename);
			$spreadsheet->setActiveSheetIndex(0);
			return $spreadsheet->getActiveSheet()->toArray(null, false);
		} catch (Throwable $t) {
			throw new Exception('Формат файла не поддерживается. Ошибка '.$t->getMessage());
		}
	}

	/**
	 * Импорт во временную таблицу
	 * @return bool
	 * @throws Exception
	 */
	public function preload():bool {
		$dataArray = $this->loadXls();
		$rowIndex = 0;
		foreach ($dataArray as $importRow) {
			$rowIndex++;
			if ($this->skipRows >= $rowIndex) continue;
			if ($this->skipEmptyRows && [] === array_filter($importRow)) continue;//ignore empty rows
			$importRow = array_map("trim", $importRow);
			$rawDataImport = new Import([
				'data' => serialize($importRow),
				'domain' => $this->domain,
				'model' => $this->model
			]);
			if (!$rawDataImport->save()) {
				throw new Exception(TemporaryHelper::Errors2String($rawDataImport->errors));
			}
		}
		return true;
	}

	/**
	 * @param-out array $messages
	 * @param array $messages
	 * @return bool
	 * @throws Throwable
	 */
	public function import(array &$messages = []):bool {
		/** @var Import $data */
		if ([] === $data = Import::find()->where(['domain' => $this->domain, 'processed' => false])->limit($this->importChunkSize)->all()) {
			return true;
		}
		foreach ($data as $importRecord) {
			$importRow = unserialize($importRecord->data, ['allowed_classes' => false]);
			$mappedColumnData = [];
			foreach ($importRow as $columnIndex => $value) {
				/** @var array $currentRule */
				if ((null === $currentRule = ArrayHelper::getValue($this->mappingRules, $columnIndex)) || !is_array($currentRule)) continue;

				if (null !== $foreignClass = ArrayHelper::getValue($currentRule, 'foreign.class')) {//вставить данные во внешнюю таблицу и связать их напрямую
					if (null !== $foreignModel = self::addInstance($foreignClass, [ArrayHelper::getValue($currentRule, 'foreign.attribute', new Exception('Foreign attribute parameter is required')) => $value])) {
						if (null === $return = ArrayHelper::getValue($currentRule, 'foreign.key')) {
							$value = $foreignModel->primaryKey;
						} else {
							$value = $foreignModel->$return;
						}
					} else {//ошибка вставки во внешнюю таблицу
						$messages[$data->id] = $foreignModel->errors;
					}
				}
				$mappedColumnData[$currentRule['attribute']] = $value;

			}
			if (null !== $result = self::addInstance($this->model, $mappedColumnData)) {
				$importRecord->processed = true;
				$importRecord->save();
			} else {
				$messages[$data->id] = $result->errors;
			}

		}
		return true;
	}

	/**
	 * @param string $class
	 * @param array $searchCondition
	 * @param array|null $fields
	 * @param bool $forceUpdate
	 * @return ActiveRecord|null
	 */
	private static function addInstance(string $class, array $searchCondition, ?array $fields = null, bool $forceUpdate = false):?ActiveRecord {
		/** @var ActiveRecord $class */
		$instance = $class::find()->where($searchCondition)->one();
		$instance = $instance??new $class();
		if ($instance->isNewRecord || $forceUpdate) {
			$instance->load($fields??$searchCondition, '');
			if (!$instance->save()) {
				return null;
			}
		}
		return $instance;
	}

	/**
	 * Подчищаем обработанные данные
	 */
	public function clear():void {
		Import::deleteAll(['model' => $this->model, 'domain' => $this->domain, 'processed' => true]);
	}

	/**
	 * @return string|null
	 * @throws Throwable
	 */
	public function getFilename():?string {
		if (null !== $lastFileName = ArrayHelper::getValue($this->files(['importFile']), 0)) {
			/** @var FileStorage $lastFileName */
			return $lastFileName->path;
		}
		return null;
	}

}