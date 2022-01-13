<?php
declare(strict_types = 1);

namespace app\modules\recaptcha\components;

use app\modules\recaptcha\components\exceptions\RecaptchaErrors;
use RuntimeException;
use yii\helpers\ArrayHelper;
use yii\httpclient\Response;
use yii\web\HttpException;

/**
 * Class ResponseHandler
 * @package app\modules\recaptcha\components
 */
class ResponseHandler {
	public const OK_CODE = '200';

	/**
	 * @param Response $response
	 * @return array
	 * @throws HttpException
	 * @throws RecaptchaErrors
	 */
	public static function handle(Response $response):array {
		if (self::OK_CODE !== $response->statusCode) {
			throw new HttpException("Код ответа от Google reCaptcha {$response->statusCode}");
		}

		if (null === $content = json_decode($response->content, true, 512, JSON_OBJECT_AS_ARRAY)) {
			throw new RuntimeException('Не получилось распознать ответ от сервера');
		}

		if ($errors = ArrayHelper::getValue($content, 'error-codes', [])) {
			throw new RecaptchaErrors($errors);
		}

		return $content;
	}

}
