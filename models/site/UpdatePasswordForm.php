<?php
declare(strict_types = 1);

namespace app\models\site;

use app\models\sys\users\Users;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * Модель смены пароля пользователя.
 *
 * @property Users $user
 * @property string $oldPassword Предыдущий пароль
 * @property string $newPassword Новый пароль
 * @property string $newPasswordRepeat Повтор пароля для самопроверки
 * @property bool $requireOldPassword Флаг проверки предыдущего пароля; нужен при сбросе забытого пароля
 */
class UpdatePasswordForm extends Model {
	public $oldPassword;
	public $newPassword;
	public $newPasswordRepeat;
	public $requireOldPassword = true;

	/** @var Users */
	private $_user;

	/**
	 * @inheritDoc
	 */
	public function rules():array {
		return [
			[['requireOldPassword'], 'boolean'],
			[['oldPassword'], 'required', 'when' => function(UpdatePasswordForm $model, string $attribute) {
				return $model->requireOldPassword;
			}],
			[['newPassword', 'newPasswordRepeat'], 'required'],
			[['newPasswordRepeat'], function(string $attribute):void {
				if (!$this->hasErrors() && $this->newPassword !== $this->newPasswordRepeat) {
					$this->addError('newPasswordRepeat', 'Введённые пароли должны совпадать');
				}
			}],
			[['oldPassword'], function(string $attribute):void {
				if ($this->requireOldPassword && !$this->hasErrors() && !$this->user->validatePassword($this->oldPassword)) {
					$this->addError('oldPassword', 'Текущий пароль введён неверно');
				}
			}]

		];
	}

	/**
	 * @inheritDoc
	 */
	public function attributeLabels():array {
		return [
			'oldPassword' => 'Текущий пароль',
			'newPassword' => 'Новый пароль',
			'newPasswordRepeat' => 'Проверочный ввод нового пароля'
		];
	}

	/**
	 * Обновляет пароль пользователя
	 * @return bool
	 */
	public function doUpdate():bool {
		if ($this->validate()) {
			$this->user->password = $this->newPassword;
			$this->user->is_pwd_outdated = false;
			return $this->user->save();
		}
		return false;
	}

	/**
	 * @return Users
	 * @throws InvalidConfigException
	 */
	public function getUser():Users {
		if (null === $this->_user) {
			throw new InvalidConfigException('Не указан пользователь для смены пароля');
		}
		return $this->_user;
	}

	/**
	 * @param Users $user
	 * @return void
	 */
	public function setUser(Users $user):void {
		$this->_user = $user;
	}

}
