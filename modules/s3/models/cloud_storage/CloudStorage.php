<?php
declare(strict_types = 1);

namespace app\modules\s3\models\cloud_storage;

use app\modules\s3\models\cloud_storage\active_record\CloudStorageAR;

/**
 * Class CloudStorage
 */
class CloudStorage extends CloudStorageAR {
	/** Максимальный размер файла для загрузки = 42 МБ */
	private const MAX_FILE_UPLOAD_SIZE = 44040192;
	public $file;

	/**
	 * @inheritDoc
	 */
	public function rules():array {
		return array_merge(
			parent::rules(),
			[['file', 'file', 'maxSize' => self::MAX_FILE_UPLOAD_SIZE]]
		);
	}
}
