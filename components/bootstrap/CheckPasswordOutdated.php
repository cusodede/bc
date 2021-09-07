<?php

namespace app\components\bootstrap;

use app\controllers\SiteController;
use app\models\sys\users\Users;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\Response;

/**
 * Class CheckPasswordOutdated
 * @package app\components\bootstrap
 */
class CheckPasswordOutdated implements BootstrapInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function bootstrap($app): void
	{
		$app->response->on(Response::EVENT_BEFORE_SEND, static function (Event $event) {
			$user = Users::Current();
			if ($user->is_pwd_outdated && 'site/update-password' !== Yii::$app->request->pathInfo) {
				/** @var Response $response */
				$response = $event->sender;
				$response->redirect(SiteController::to('update-password'));
			}
		});
	}
}