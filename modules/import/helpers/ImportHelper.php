<?php
declare(strict_types = 1);

namespace app\modules\import\helpers;

use app\modules\import\exceptions\HeadersError;
use app\modules\import\models\active_record\ImportStatus;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\SheetInterface;
use DateTime;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date as OfficeDate;
use Throwable;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

/**
 * class ImportHelper
 * Методы, которые часто используем в обработчиков. Например: парсинг xls файла, проверка на соответствие хэдера и т.д.
 */
class ImportHelper {

	/**
	 * Парсинг XLS в массив
	 * @param string $filePath
	 * @param bool $calculateFormula Получать значения по формулам. Поведение для spout не проверялось.
	 * @param bool $fallbackLoader true for phpspreadsheet, false for spout
	 * @return array
	 * @throws Exception Исключение отлавливаем тут, и прокидываем вверх для возможности установки своего обработчика
	 */
	public static function loadXls(string $filePath, bool $calculateFormula = true, bool $fallbackLoader = false):array {
		try {
			if ($fallbackLoader) {
				$reader = new Xlsx();
				$reader->setReadDataOnly(true);
				$spreadsheet = $reader->load($filePath);
				$spreadsheet->setActiveSheetIndex(0);
				return $spreadsheet->getActiveSheet()->toArray(null, $calculateFormula);
			}
			$result = [];
			$reader = ReaderEntityFactory::createReaderFromFile($filePath);
			$reader->open($filePath);
			/** @var SheetInterface $sheet */
			foreach ($reader->getSheetIterator() as $sheet) {
				if (0 === $sheet->getIndex()) {
					/** @var Row $row */
					foreach ($sheet->getRowIterator() as $row) {
						$rowCellValues = [];
						foreach ($row->getCells() as $cell) {
							$rowCellValues[] = $cell->getValue();
						}
						$result[] = $rowCellValues;
					}
					$reader->close();
					return $result;
				}
			}
			$reader->close();
		} catch (Throwable $t) {
			throw new Exception('Ошибка при разборе файла: '.$t->getMessage());
		}
		return [];
	}

	/**
	 * В зависимости от используемого обработчика и его настроек дата может прилететь в любом формате; кукожим в нужный
	 * строковый формат.
	 * @param mixed $value
	 * @param string $format
	 * @return string|null
	 */
	public static function asDateString(mixed $value, string $format = 'Y-m-d'):?string {
		if (empty($value)) return null;
		try {
			if (is_string($value)) return $value;#suppose it already formatted
			if (is_a($value, DateTime::class)) return $value->format($format);#already DateTime from spout
			if (is_float($value) || is_int($value)) OfficeDate::excelToDateTimeObject($value)->format($format);#MS Excel serialized date/time value from phpspreadsheet
		} catch (Throwable) {
			return null;
		}
		return null;
	}

	/**
	 * Убираем хэдер, не нужные индексы и пустые строки
	 * @param array $data
	 * @param int $columnNum
	 * @param int|null $offset
	 * @return array
	 */
	public static function removeUnnecessaryData(array $data, int $columnNum, ?int $offset = null):array {
		if (null !== $offset) {
			$data = array_slice($data, $offset);// убираем строки которые не нужны (обычно это хедеры)
		}

		$data = array_map(static function($row) use ($columnNum) { // получаем только те столбцы, которые нам нужно
			return array_slice($row, 0, $columnNum);
		}, $data);
		return array_filter($data, static function($row) {
			return [] !== array_filter($row);  // убираем пустые строки
		});
	}

	/**
	 * Чтобы случайно не загружали файл который не относится к этому загрузчику, проверяем хэдер
	 * Это не панацея, но поможет
	 * @param array $headers
	 * @param array $schema
	 * @throws HeadersError
	 */
	public static function checkHeader(array $headers, array $schema):void {
		foreach ($headers as $key => $header) {
			if (trim($header) !== $schema[$key]) {
				throw new HeadersError('Заголовки колонок отличаются');
			}
		}
	}

	/**
	 * @param string $class
	 * @param array $searchCondition - условия для поиска уже существующих записей
	 * @param array|null $fields
	 * @param bool $forceUpdate
	 * @param array $errors
	 * @return ActiveRecord|null
	 */
	public static function addInstance(string $class, array $searchCondition, ?array $fields = null, bool $forceUpdate = false, array &$errors = []):?ActiveRecord {
		/** @var ActiveRecord $class */
		$instance = $class::find()->where($searchCondition)->one();
		$instance = $instance??new $class();
		if ($instance->isNewRecord || $forceUpdate) {
			$instance->load($fields??$searchCondition, '');
			if (!$instance->save()) {
				$errors = $instance->errors;
				return null;
			}
		}
		return $instance;
	}

	/**
	 * Создаем/обновляем записи
	 * @param string $class
	 * @param array $columns
	 * @param array $batchArray
	 * @param array $errors
	 * @return int|null
	 */
	public static function batchInsert(string $class, array $columns, array $batchArray, array &$errors):?int {
		$rowsCount = 0;
		foreach ($batchArray as $record) {
			if (null !== self::addInstance($class, array_intersect_key($record, array_flip($columns)), $record, true, $errors)) {
				$rowsCount++;
			} else {
				return null;
			}
		}
		return $rowsCount;
	}

	/**
	 * @param ActiveRecord $model
	 * @param string $filename
	 * @return ImportStatus
	 * @throws ServerErrorHttpException
	 */
	public static function createImportStatusRecord(ActiveRecord $model, string $filename):ImportStatus {
		$importStatus = new ImportStatus();
		$importStatus->attributes = [
			'model' => get_class($model),
			'domain' => time(),
			'filename' => $filename
		];

		if (false === $importStatus->save()) {
			throw new ServerErrorHttpException('Could not record the import');
		}

		return $importStatus;
	}
}