<?php
declare(strict_types = 1);

namespace app\modules\export\helpers;

use app\modules\export\models\SysExport;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Box\Spout\Writer\WriterInterface;
use pozitronik\filestorage\models\FileStorage;
use pozitronik\helpers\DateHelper;
use pozitronik\helpers\PathHelper;
use Throwable;
use yii\base\Exception;

/**
 * class ExportHelper
 * Методы, которые часто используем в обработчиков.
 */
class ExportHelper {

	/**
	 * Creates a writer and returns it
	 * @param string $filePath
	 * @param string $type
	 * @return WriterInterface
	 * @throws Exception
	 */
	public static function createFile(string $filePath, string $type = 'xls'):WriterInterface {
		try {
			$writer = match ($type) {
				'xls' => WriterEntityFactory::createXLSXWriter(),
				'ods' => WriterEntityFactory::createODSWriter(),
				'csv' => WriterEntityFactory::createCSVWriter(),
				default => throw new Exception('Wrong file type ')
			};

			if (!PathHelper::CreateDirIfNotExisted(pathinfo($filePath, PATHINFO_DIRNAME))) {
				throw new Exception('Can not create dir!');
			}

			$writer->openToFile($filePath);
		} catch (Throwable $t) {
			throw new Exception('Ошибка : '.$t->getMessage());
		}
		return $writer;
	}

	/**
	 * Add a row at a time
	 * @param WriterInterface $writer
	 * @param array $row
	 * @throws IOException
	 * @throws WriterNotOpenedException
	 */
	public static function addSingleRow(WriterInterface $writer, array $row):void {
		$singleRow = WriterEntityFactory::createRow(self::prepareRow($row));
		$writer->addRow($singleRow);
	}

	/**
	 * @param array $rawRow
	 * @return array
	 */
	public static function prepareRow(array $rawRow):array {
		$row = [];
		foreach ($rawRow as $cell) {
			$row[] = WriterEntityFactory::createCell($cell);
		}
		return $row;
	}

	/**
	 * Add multiple rows at a time
	 * @param WriterInterface $writer
	 * @param array $rows
	 * @throws IOException
	 * @throws InvalidArgumentException
	 * @throws WriterNotOpenedException
	 */
	public static function addMultiRows(WriterInterface $writer, array $rows):void {
		$multipleRows = [];
		foreach ($rows as $row) {
			$multipleRows[] = WriterEntityFactory::createRow(self::prepareRow($row));
		}
		$writer->addRows($multipleRows);
	}

	/**
	 * В связи с тем что мы начинаем хранить все наши файлы в объектное хранилище, этот метод будет @param string $filePath
	 * @param int $modelKey
	 * @param int $user
	 * @param array $tags
	 * @throws Exception
	 * @deprecated. Но пока что его оставляем.
	 */
	public static function saveFileStorageModel(string $filePath, int $modelKey, int $user, array $tags):void {
		$fileStorage = new FileStorage([
			'name' => basename($filePath),
			'path' => $filePath,
			'model_name' => SysExport::class,
			'model_key' => $modelKey,
			'at' => DateHelper::lcDate(),
			'daddy' => $user,
			'tags' => $tags
		]);

		if (!$fileStorage->saveRecord()) {
			throw new Exception('Can not save model FileStorage.'.json_encode($fileStorage->errors));
		}
	}

	/**
	 * @param int $user
	 * @return SysExport
	 * @throws Exception
	 */
	public static function createExportModel(int $user):SysExport {
		$exportModel = new SysExport(['user' => $user]);
		if (false === $exportModel->save()) {
			throw new Exception('Error while creating record'.json_encode($exportModel->errors));
		}
		return $exportModel;
	}
}