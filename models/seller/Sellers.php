<?php
declare(strict_types = 1);

namespace app\models\seller;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\seller\active_record\SellersAR;
use app\models\sys\users\Users;
use pozitronik\filestorage\traits\FileStorageTrait;

/**
 * Class Sellers
 * Конкретный продавец
 * @property mixed $sellerDocs атрибут загрузки файла
 */
class Sellers extends SellersAR {
	use FileStorageTrait;
	use ActiveRecordTrait;

	public $sellerDocs;

	/**
	 * Создает пользователя на основе созданного продавца
	 * @param array|null $errors
	 * @return int|null
	 */
	public function createUser(?array &$errors = []):?int {
		$newUser = new Users();
		$newUser->setAndSaveAttributes([
			'login' => $this->login,
			'username' => trim("{$this->surname} {$this->name} {$this->patronymic}"),
			'password' => Users::getDefaultPassword(),
			'comment' => "User automatically created from seller's registration",
			'email' => $this->email
		]);

		$errors = $newUser->errors;

		return $newUser->id??null;
	}

	/**
	 * Связка продавец-пользователь
	 * @param $userId
	 */
	public function linkToUsers($userId):void {
		$this->user = $userId;
		$this->save(false);
	}
}