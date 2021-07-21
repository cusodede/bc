<?php
declare(strict_types = 1);

namespace app\models\partners;

use app\models\partners\active_record\Partners as ActiveRecordPartners;
use app\validators\ImageValidator;
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
			[['logo'], ImageValidator::class, 'extensions' => 'png, svg, jpg, jpeg', 'skipOnEmpty' => false, 'maxSize' => 1024 * 1024],
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