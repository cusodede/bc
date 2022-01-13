<?php
declare(strict_types = 1);

namespace app\modules\recaptcha\services;

use app\modules\recaptcha\components\exceptions\RecaptchaErrors;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception as HttpClientException;
use yii\web\HttpException;

/**
 * Class RecaptchaInterface
 */
interface RecaptchaInterface {
	/**
	 * @param array $data
	 * @return array
	 * @throws HttpClientException
	 * @throws InvalidConfigException
	 * @throws RecaptchaErrors
	 * @throws HttpException
	 */
	public function checkRecaptcha(array $data):array;

}
