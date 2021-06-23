<?php
declare(strict_types = 1);

namespace app\modules\api\controllers;

use app\models\sys\permissions\filters\PermissionFilter;
use app\models\sys\users\Users;
use app\modules\api\authenticators\HttpBasicCredentialsAuth;
use app\modules\api\tokenizers\grant_types\GrantTypeInterface;
use app\modules\api\tokenizers\grant_types\GrantTypeIssue;
use app\modules\api\tokenizers\grant_types\GrantTypeRefresh;
use app\modules\api\tokenizers\JwtTokenizer;
use pozitronik\sys_exceptions\models\LoggedException;
use pozitronik\sys_exceptions\models\SysExceptions;
use Throwable;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller as YiiRestController;
use yii\web\Response;

/**
 * Class AuthController
 * @package app\modules\api\controllers
 */
class AuthController extends YiiRestController
{
	public const GRANT_TYPE_CREDENTIALS = 'client_credentials';
	public const GRANT_TYPE_REFRESH     = 'refresh_token';

	/**
	 * {@inheritdoc}
	 */
	public function behaviors(): array
	{
		return [
			'contentNegotiator' => [
				'class'   => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON
				],
			],
			'authenticator' => [
				'class' => HttpBasicCredentialsAuth::class
			],
			'verbFilter' => [
				'class'   => VerbFilter::class,
				'actions' => $this->verbs(),
			],
			'access' => [
				'class' => PermissionFilter::class
			]
		];
	}

	/**
	 * @return array
	 * @throws Throwable
	 * @throws LoggedException
	 */
	public function actionToken(): array
	{
		$user = Users::Current();

		$grantType = $this->getGrantType();
		$grantType->loadRequest(Yii::$app->request);
		try {
			$tokenizer = new JwtTokenizer($user, $grantType);

			return $tokenizer->getTokenData();
		} catch (Throwable $e) {
			SysExceptions::log($e, true);
		}

		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	protected function verbs(): array
	{
		return ['token' => ['GET', 'POST']];
	}

	private function getGrantType(): GrantTypeInterface
	{
		$grantType = Yii::$app->request->post('grant_type');
		if ($grantType === self::GRANT_TYPE_REFRESH) {
			return new GrantTypeRefresh();
		}

		return new GrantTypeIssue();
	}
}