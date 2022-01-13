<?php
declare(strict_types = 1);

namespace app\components\authorization;

use app\components\helpers\ArrayHelper;
use app\controllers\SiteController;
use Yii;
use yii\base\BootstrapInterface;

/**
 * Class CheckPasswordOutdated
 * @package app\components\authorization
 */
class CheckPasswordOutdated implements BootstrapInterface {
	/**
	 * Суть: пользователь прошел аутентификацию, но должен менять пароль. Технический пользователь внутри системы, так
	 * что он может переходить по любому ему доступному УРЛ и не менять пароль. Это плохо. Так что каждый раз для
	 * аутентифицированных пользователей проверяем поле is_pwd_outdated и редиректим если он пароль не менял.
	 * Эта проверка нужна только для входа в админку бэка, поэтому проверяем authorization header. Фронт или любой
	 * другой сервис будет ходить через АПИ.
	 * @inheritDoc
	 */
	public function bootstrap($app):void {
		if ((null !== ArrayHelper::getValue(Yii::$app->request->headers, 'authorization')) &&
			(null !== $user = Yii::$app->user->identity) &&
			$user->is_pwd_outdated &&
			'site/update-password' !== Yii::$app->request->pathInfo) {
			Yii::$app->response->redirect(SiteController::to('update-password'));
		}
	}
}