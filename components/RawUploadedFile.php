<?php
declare(strict_types = 1);

namespace app\components;

use app\components\helpers\FileHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\UploadedFile;

/**
 * Класс для обозначения файла, сгенерированного мануально, а не через дефолтную реализацию [[UploadedFile::getInstance()]].
 * Class RawUploadedFile
 * @package app\components
 */
class RawUploadedFile extends UploadedFile
{
	/**
	 * RawUploadedFile constructor.
	 * @param string $path
	 * @throws InvalidConfigException
	 */
	public function __construct(string $path)
	{
		parent::__construct([
			'name' => basename($path),
			'type' => FileHelper::getMimeType($path, null, false),
			'size' => filesize($path),
			'tempName' => $path,
			'error' => UPLOAD_ERR_OK
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function saveAs($file, $deleteTempFile = true): bool
	{
		if ($this->hasError) {
			return false;
		}

		$targetFile = Yii::getAlias($file);

		return $deleteTempFile ? rename($this->tempName, $targetFile) : copy($this->tempName, $targetFile);
	}
}