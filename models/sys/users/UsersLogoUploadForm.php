<?php
declare(strict_types = 1);

namespace app\models\sys\users;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class LogoUploadForm
 * @package app\models\sys\users
 */
class UsersLogoUploadForm extends Model
{
	/**
	 * @var UploadedFile|null
	 */
	public ?UploadedFile $uploadedFile;
	/**
	 * @var int|null
	 */
	public ?int $userId;

	public function rules(): array
	{
		return [
			[['uploadedFile'], 'file', 'skipOnEmpty' => false, 'mimeTypes' => 'image/png', 'maxSize' => 1024 * 1024],
			[['userId'], 'required'],
			[['userId'], 'integer']
		];
	}

	public function upload(string &$error = null): bool
	{
		if ($this->validate()) {
			$dir = Yii::getAlias("@webroot/img/avatars/{$this->userId}");
			FileHelper::createDirectory($dir);

			$this->uploadedFile->saveAs($dir . DIRECTORY_SEPARATOR . 'avatar.png');
			return true;
		}

		$error = 'Ошибка обработки изображения';

		return false;
	}
}