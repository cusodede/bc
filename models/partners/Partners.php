<?php
declare(strict_types = 1);

namespace app\models\partners;

use app\components\validators\ImageValidator;
use app\models\partners\active_record\Partners as ActiveRecordPartners;
use pozitronik\filestorage\models\FileStorage;
use pozitronik\filestorage\traits\FileStorageTrait;
use Throwable;
use yii\helpers\ArrayHelper;

/**
 * Class Partners
 * @package app\models\partners
 *
 * @property-read FileStorage|null $fileLogo
 */
class Partners extends ActiveRecordPartners
{
	use FileStorageTrait;

	/**
	 * @var mixed атрибут для загрузки логотипа партнера.
	 */
	public $logo;

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return array_merge(parent::rules(), [
			[['logo'], ImageValidator::class, 'skipOnEmpty' => false,
				'extensions' => 'png, svg, jpg, jpeg',
				'maxSize' => 1024 * 1024,
				'minHeight' => 300,
				'maxHeight' => 300,
				'minWidth' => 300,
				'maxWidth' => 300
			],
		]);
	}

	/**
	 * @return FileStorage|null
	 * @throws Throwable
	 */
	public function getFileLogo(): ?FileStorage
	{
		return ([] !== $files = $this->files(['logo'])) ? ArrayHelper::getValue($files, 0) : null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return array_merge(parent::attributeLabels(), [
			'logo' => 'Логотип'
		]);
	}
}