<?php
declare(strict_types = 1);

namespace app\models\sys\users;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\sys\permissions\traits\UsersPermissionsTrait;
use app\models\sys\users\active_record\Users as ActiveRecordUsers;
use pozitronik\sys_exceptions\models\LoggedException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;
use yii\web\IdentityInterface;

/**
 * Class Users
 * Авторизация, идентификация, доступы, прочие пользовательские функции, не относящиеся к ActiveRecord
 *
 * @property-read bool $isSaltedPassword Для удобства разрешено не использовать соль при установлении пароля
 * @property-read string $authKey @see [[yii\web\IdentityInterface::getAuthKey()]]
 */
class Users extends ActiveRecordUsers implements IdentityInterface {
	use UsersPermissionsTrait;
	use ActiveRecordTrait;

	public $newPassword;
	private const DEFAULT_PASSWORD = 'Qq123456';

	/**
	 * @inheritDoc
	 */
	public function attributeLabels():array {
		return array_merge(parent::attributeLabels(), [
			'newPassword' => 'Новый пароль'
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function rules():array {
		return array_merge(parent::rules(), [
			[['newPassword'], 'string', 'max' => 255]
		]);
	}

	/**
	 * @return static
	 * @throws LoggedException
	 */
	public static function Current():self {
		if (null === $user = self::findIdentity(Yii::$app->user->id)) {
			throw new LoggedException(new ForbiddenHttpException('Пользователь не авторизован'));
		}
		return $user;
	}

	/**
	 * @param string $login
	 * @return Users|null
	 */
	public static function findByLogin(string $login):?Users {
		return self::findOne(['login' => $login]);
	}

	/**
	 * @inheritDoc
	 */
	public static function findIdentity($id) {
		return static::findOne($id);
	}

	/**
	 * Finds an identity by the given token.
	 * @param string $token the token to be looked for
	 * @param null|HttpBearerAuth $type the type of the token. The value of this parameter depends on the implementation.
	 * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
	 * @return IdentityInterface|null the identity object that matches the given token.
	 * Null should be returned if such an identity cannot be found
	 * or the identity is not in an active state (disabled, deleted, etc.)
	 */
	public static function findIdentityByAccessToken($token, $type = null):?IdentityInterface {
		return static::findOne(['access_token' => $token]);
	}

	/**
	 * @return string
	 */
	private static function generateSalt():string {
		return sha1(uniqid((string)mt_rand(), true));
	}

	/**
	 * @param null|string $password
	 * @return string
	 */
	private function doSalt(?string $password):?string {
		return null === $password?null:sha1($password.$this->salt);
	}

	/**
	 * {@inheritDoc}
	 */
	public function beforeValidate():bool {
		if ($this->isNewRecord) {
			$this->password = $this->password??self::DEFAULT_PASSWORD;
			$this->is_pwd_outdated = true;

		}
		if ($this->isAttributeUpdated('password')) {/*если пароль обновился, то пересолим*/
			$this->salt = self::generateSalt();
			$this->password = $this->doSalt($this->password);
		}
		return parent::beforeValidate();
	}

	/**
	 * @return string
	 */
	public function getAuthKey():string {
		return md5($this->id.md5($this->login));
	}

	/**
	 * Returns an ID that can uniquely identify a user identity.
	 * @return int an ID that uniquely identifies a user identity.
	 */
	public function getId():int {
		return $this->id;
	}

	/**
	 * Validates the given auth key.
	 *
	 * @param string $authKey the given auth key
	 * @return bool whether the given auth key is valid.
	 * @see getAuthKey()
	 */
	public function validateAuthKey($authKey):bool {
		return $this->authKey === $authKey;
	}

	/**
	 * @return bool
	 */
	public function getIsSaltedPassword():bool {
		return null !== $this->salt;
	}

	/**
	 * Проверка пароля.
	 * Учитывает наличие соли; если соли нет, то сверяемся только с паролем (удобно для сброса УД прямо в БД)
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword(string $password):bool {
		return $this->isSaltedPassword?$this->doSalt($password) === $this->password:$this->password === $password;
	}

}