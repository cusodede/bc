<?php
declare(strict_types = 1);

namespace app\components\validators;

use app\components\helpers\FileHelper;
use yii\validators\ImageValidator as YiiImageValidator;
use yii\web\UploadedFile;

/**
 * Class ImageValidator
 * @package app\validators
 */
class ImageValidator extends YiiImageValidator
{
	/**
	 * {@inheritdoc}
	 */
	public function validateAttribute($model, $attribute): void
	{
		$value = $model->$attribute;
		if (is_string($value) && '' !== $value) {
			//почему-бы и не предусмотреть возможность использования заранее сгенерированного файла
			if (file_exists($value)) {
				$uploadedFile = FileHelper::createUploadedFileInstance($value);
			} else {
				$uploadedFile = FileHelper::createUploadedFileInstance(FileHelper::createTmpFromRaw($value));
			}

			$model->$attribute = ($this->maxFiles > 1) ? [$uploadedFile] : $uploadedFile;
		} else {
			$model->$attribute = ($this->maxFiles > 1) ? UploadedFile::getInstances($model, $attribute) : UploadedFile::getInstance($model, $attribute);
		}

		parent::validateAttribute($model, $attribute);
	}
}