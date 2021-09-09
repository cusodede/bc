<?php
declare(strict_types = 1);

namespace app\components\bootstrap;

use app\controllers\SiteController;
use app\models\sys\users\Users;
use app\modules\api\ApiModule;
use app\modules\graphql\GraphqlModule;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\ForbiddenHttpException;
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
		$app->response->on(Response::EVENT_BEFORE_SEND, function(Event $event) use ($app) {
			if ($this->validateEnvironment($app)) {
				/** @var Response $response */
				$response = $event->sender;
				$response->redirect(SiteController::to('update-password'));
			}
		});
	}

	/**
	 * @param Application $app
	 * @return bool
	 */
	private function validateEnvironment(Application $app): bool
	{
		return $this->validateUser() && $this->validateModules($app) && $this->validatePathInfo($app);
	}

	/**
	 * @return bool
	 */
	public function validateUser(): bool
	{
		try {
			$user = Users::Current();
		} catch (ForbiddenHttpException) {
			return false;
		}

		return $user->is_pwd_outdated;
	}

	/**
	 * @param Application $app
	 * @return bool
	 */
	private function validateModules(Application $app): bool
	{
		return (!$app->controller->module instanceof ApiModule) && (!$app->controller->module instanceof GraphqlModule);
	}

	/**
	 * @param Application $app
	 * @return bool
	 */
	private function validatePathInfo(Application $app): bool
	{
		return !in_array($app->request->pathInfo, ['site/update-password', 'site/logout'], true);
	}
}