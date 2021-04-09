<?php
declare(strict_types = 1);

namespace app\models\sys\users;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\sys\permissions\traits\UsersPermissionsTrait;
use app\models\sys\users\active_record\Users as ActiveRecordUsers;

/**
 * Class Users
 */
class Users extends ActiveRecordUsers {
	use UsersPermissionsTrait;
	use ActiveRecordTrait;

	/**
	 * @param string $login
	 * @return Users|null
	 */
	public static function findByLogin(string $login):?Users {
		return self::findOne(['login' => $login]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function beforeValidate():bool {
		if ($this->isNewRecord) {
			if (null === $this->salt) {
				$this->salt = sha1(uniqid((string)mt_rand(), true));
				$this->password = sha1($this->password.$this->salt);
			}
		} elseif (null !== $this->update_password) {
			$this->salt = sha1(uniqid((string)mt_rand(), true));
			$this->password = sha1($this->update_password.$this->salt);
		}
		return parent::beforeValidate();
	}

	/**
	 * @return string
	 */
	public function getAuthKey():string {
		return md5($this->id.md5($this->login));
	}
}