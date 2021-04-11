<?php
declare(strict_types = 1);

namespace app\models\site;

use app\models\sys\users\Users;
use pozitronik\helpers\DateHelper;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property Users|null $user
 * @property string $login
 * @property string $password
 * @property bool $rememberMe
 * @property string $email
 * @property bool $restore
 */
class LoginForm extends Model {
	public $login;
	public $password;
	public $rememberMe = true;
	public $restore = false;

	/** @var null|Users */
	private $_user;

	/**
	 * @return array the validation rules.
	 */
	public function rules():array {
		return [
			[['login', 'password'], 'required'],
			['rememberMe', 'boolean'],
			[['password'], function(string $attribute):void {
				if (!$this->hasErrors() && (null === $this->user || false === $this->user->validatePassword($this->password))) {
					$this->addError($attribute, 'Неправильные логин или пароль.');
				}
			}],
			[['login'], function(string $attribute):void {
				if (!$this->hasErrors() && null !== $this->user && $this->user->deleted) {
					$this->addError($attribute, 'Пользователь заблокирован');
				}
			}]];
	}

	/**
	 * @return array
	 */
	public function attributeLabels():array {
		return [
			'login' => 'Логин',
			'password' => 'Пароль',
			'rememberMe' => 'Запомнить'
		];
	}

	/**
	 * Logs in a user using the provided username and password.
	 * @return bool whether the user is logged in successfully
	 */
	public function doLogin():bool {
		return ($this->validate() && Yii::$app->user->login($this->user, $this->rememberMe?DateHelper::SECONDS_IN_MONTH:0));
	}

	/**
	 * @return Users|null
	 */
	public function getUser():?Users {
		if (null === $this->_user) {
			$this->_user = Users::findByLogin($this->login);
		}
		return $this->_user;
	}

}
