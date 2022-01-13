<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use app\components\authorization\FakeJwtBearerAuth;
use app\components\Options;
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
class AuthHelper {
	/**
	 * @return IdentityInterface|Users
	 * @throws ForbiddenHttpException
	 * @throws InvalidConfigException
	 * @throws NotInstantiableException
	 * @throws Throwable
	 * @throws UnauthorizedHttpException
	 */
	public static function authenticate():IdentityInterface|Users {
		if (null === $user = static::getAuthUser()) throw new ForbiddenHttpException();
		return $user;
	}

	/**
	 * @return IdentityInterface|Users|null
	 * @throws ForbiddenHttpException
	 * @throws InvalidConfigException
	 * @throws NotInstantiableException
	 * @throws Throwable
	 * @throws UnauthorizedHttpException
	 */
	public static function getAuthUser():null|IdentityInterface|Users {
		$auth = (Options::getValue(Options::GRAPHQL_IGNORE_TOKEN_VALIDATION))
			?Yii::$container->get(FakeJwtBearerAuth::class)
			:Yii::$container->get(JwtHttpBearerAuth::class);
		return $auth->authenticate(Yii::$app->user, Yii::$app->request, Yii::$app->response);
	}
}
