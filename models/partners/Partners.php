<?php
declare(strict_types = 1);

namespace app\models\partners;

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

	public $logo;

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return array_merge(parent::rules(), [
			[['logo'], 'image', 'extensions' => 'png'],
		]);
	}

	/**
	 * @return FileStorage|null
	 * @throws Throwable
	 */
	public function getFileLogo(): ?FileStorage
	{
		$files = $this->files(['logo']);
		return ([] !== $files) ? ArrayHelper::getValue($files, 0) : null;
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