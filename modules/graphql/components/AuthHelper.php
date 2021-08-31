<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use app\models\core\Options;
use app\models\sys\users\Users;
use cusodede\jwt\JwtHttpBearerAuth;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\web\ForbiddenHttpException;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;

/**
 * Class AuthTrait
 * @package app\modules\graphql\components
 */
class AuthHelper
{
	/**
	 * @return IdentityInterface|Users
	 * @throws ForbiddenHttpException
	 * @throws InvalidConfigException
	 * @throws NotInstantiableException
	 * @throws Throwable
	 * @throws UnauthorizedHttpException
	 */
	public static function authenticate(): IdentityInterface|Users
	{
		if (Options::getValue(Options::GRAPHQL_IGNORE_TOKEN_VALIDATION)) {
			return Users::Current();
		}

		$auth = Yii::$container->get(JwtHttpBearerAuth::class);
		$user = $auth->authenticate(Yii::$app->user, Yii::$app->request, Yii::$app->response);

		if (null === $user) {
			throw new ForbiddenHttpException();
		}
		return $user;
	}
}
