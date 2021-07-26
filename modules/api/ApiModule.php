<?php
declare(strict_types = 1);

namespace app\modules\api;

use app\models\sys\users\Users;
use app\modules\api\error\ErrorHandler as ApiErrorHandler;
use cusodede\jwt\JwtHttpBearerAuth;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use pozitronik\traits\traits\ModuleTrait;
use Yii;
use yii\base\Module as YiiBaseModule;
use yii\web\Response;

/**
 * Class ApiModule
 * @package app\modules\api
 */
class ApiModule extends YiiBaseModule
{
	use ModuleTrait;

	/**
	 * {@inheritdoc}
	 */
	public function init(): void
	{
		parent::init();

		//не применимо к REST API
		Yii::$app->request->enableCsrfCookie = false;
		//чтобы без сюрпризов в виде html разметки
		Yii::$app->response->format = Response::FORMAT_JSON;
		//просто как рекомендация
		Yii::$app->user->enableSession = false;

		$errorHandler = Yii::createObject(ApiErrorHandler::class);
		$errorHandler->register();

		Yii::$app->set('errorHandler', $errorHandler);

		Yii::$container->set(JwtHttpBearerAuth::class, [
			'jwtOptionsCallback' => static function(Users $user) {
				return [
					'validationConstraints' => [
						new SignedWith(Yii::$app->jwt->signer, Yii::$app->jwt->signerKey),
						LooseValidAt::class
					]
				];
			}
		]);
	}
}