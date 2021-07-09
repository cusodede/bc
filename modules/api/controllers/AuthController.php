<?php
declare(strict_types = 1);

namespace app\modules\api\controllers;

use app\models\sys\permissions\filters\PermissionFilter;
use app\models\sys\users\Users;
use app\modules\api\authenticators\HttpBasicCredentialsAuth;
use app\modules\api\tokenizers\grant_types\BaseGrantType;
use app\modules\api\tokenizers\grant_types\GrantTypeIssue;
use app\modules\api\tokenizers\grant_types\GrantTypeRefresh;
use app\modules\api\tokenizers\JwtTokenizer;
use app\modules\api\use_cases\InvalidateUserByTokenCase;
use cusodede\jwt\JwtHttpBearerAuth;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller as YiiRestController;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class AuthController
 * @package app\modules\api\controllers
 */
class AuthController extends YiiRestController
{
	public const GRANT_TYPE_PASSWORD = 'password';
	public const GRANT_TYPE_REFRESH  = 'refresh_token';

	/**
	 * {@inheritdoc}
	 */
	public function behaviors(): array
	{
		return [
			'contentNegotiator'  => [
				'class'   => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON
				]
			],
			'authenticatorBasic' => [
				'class' => HttpBasicCredentialsAuth::class,
				'only'  => ['token']
			],
			'authenticatorJwt'   => [
				'class' => JwtHttpBearerAuth::class,
				'only'  => ['logout']
			],
			'verbFilter'         => [
				'class'   => VerbFilter::class,
				'actions' => $this->verbs()
			],
			'access'             => [
				'class' => PermissionFilter::class
			]
		];
	}

	/**
	 * Запрос на получение токена доступа к API.
	 * @return array
	 * @throws BadRequestHttpException
	 */
	public function actionToken(): array
	{
		return (new JwtTokenizer($this->getGrantType()))->tokenData;
	}

	/**
	 * Принудительная инвалидация токена доступа пользователя.
	 * @return void
	 * @throws BadRequestHttpException
	 * @throws Throwable
	 * @throws StaleObjectException
	 * @throws ForbiddenHttpException
	 */
	public function actionLogout(): void
	{
		/**
		 * @var Users $user
		 * [[Users::current()]] не годится, т.к. мы уже имеем преднастроенный identity
		 */
		$user = Yii::$app->user->identity;

		$case = new InvalidateUserByTokenCase();
		$case->execute($user, $user->identifiedToken, Yii::$app->request);

		Yii::$app->user->logout();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function verbs(): array
	{
		return ['token' => ['GET', 'POST'], 'logout' => ['GET']];
	}

	/**
	 * @return BaseGrantType
	 * @throws BadRequestHttpException
	 */
	private function getGrantType(): BaseGrantType
	{
		$grantType = Yii::$app->request->post('grant_type');
		if (self::GRANT_TYPE_REFRESH === $grantType) {
			return new GrantTypeRefresh(Yii::$app->request);
		}

		return new GrantTypeIssue(Yii::$app->request);
	}
}