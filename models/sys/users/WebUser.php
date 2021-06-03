<?php
declare(strict_types = 1);

namespace app\models\sys\users;

use DomainException;
use Throwable;
use Webmozart\Assert\Assert;
use Yii;
use yii\web\Cookie;
use yii\web\User;

/**
 * Class WebUser
 * @package app\models\vendor\yii2\web
 *
 * @property Users $identity
 */
class WebUser extends User {

	/**
	 * Вернуть id оригинального пользователя,
	 * из под которого авторизовались под другим пользователем
	 *
	 * @return int
	 */
	public function getOriginalUserId():int {
		if (!$this->isLoginAsAnotherUser()) {
			throw new DomainException("Неправильный сценарий использования");
		}

		$userId = (int)Yii::$app->request->cookies->get('fear')->value;
		if ($userId <= 0) {
			throw new DomainException("Неправильное значение оригинального id");
		}
		return $userId;
	}

	/**
	 * @return bool
	 */
	public function isLoginAsAnotherUser():bool {
		return Yii::$app->request->cookies->has('fear');
	}

	/**
	 * Вернуться в родную учетную запись
	 *
	 * @return void
	 */
	public function loginBackToOriginUser():void {
		$webUser = Yii::$app->user;
		if ($webUser->isGuest) {
			throw new DomainException("Вы не авторизованы");
		}

		$existentUser = Users::findIdentity($id = $this->getOriginalUserId());
		if (is_null($existentUser)) {
			throw new DomainException("Пользователь с id $id не найден");
		}

		$webUser->login($existentUser);
		Yii::$app->response->cookies->remove('fear');
	}

	/**
	 * Авторизоваться под другим пользователем
	 *
	 * @param int $userId
	 * @throws Throwable
	 */
	public function loginAsAnotherUser(int $userId):void {
		Assert::greaterThan($userId, 0, 'ID пользователя должен быть больше 0');
		$webUser = Yii::$app->user;
		if ($webUser->isGuest) {
			throw new DomainException("Вы не авторизованы");
		}

		if (!$webUser->identity->hasPermission(['login_as_another_user'])) {
			throw new DomainException("Вы не можете авторизоваться под другим пользователем");
		}

		$existentUser = Users::findIdentity($userId);
		if (is_null($existentUser)) {
			throw new DomainException("Пользователь с id $userId не найден");
		}

		$originalUserId = $webUser->identity->id;

		$webUser->login($existentUser);

		Yii::$app->response->cookies->add(
			new Cookie([
				'name' => 'fear',
				'value' => $originalUserId,
			])
		);
	}

	/**
	 * @param bool $destroySession
	 * @return bool
	 */
	public function logout($destroySession = true) {
		$isLogout = parent::logout($destroySession);
		if ($isLogout) {
			Yii::$app->response->cookies->remove('fear');
		}
		return $isLogout;
	}
}