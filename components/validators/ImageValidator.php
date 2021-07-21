<?php
declare(strict_types = 1);

namespace app\validators;

use app\components\helpers\FileHelper;
use yii\validators\ImageValidator as YiiImageValidator;

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
		if (is_string($value = $model->$attribute)) {
			//почему-бы и не предусмотреть возможность использования заранее сгенерированного файла
			if (file_exists($value)) {
				$uploadedFile = FileHelper::createUploadedFileInstance($value);
			} else {
				$uploadedFile = FileHelper::createUploadedFileInstance(FileHelper::createTmpFromRaw($value));
			}

			$model->$attribute = ($this->maxFiles > 1) ? [$uploadedFile] : $uploadedFile;
		}

		parent::validateAttribute($model, $attribute);
	}
}