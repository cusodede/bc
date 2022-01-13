<?php
declare(strict_types = 1);

namespace app\modules\recaptcha\services;

use app\modules\recaptcha\components\ResponseHandler;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception as HttpClientException;
use yii\httpclient\Response;
use yii\base\InvalidConfigException;

/**
 * @package app\modules\recaptcha\services
 */
class RecaptchaService extends Model implements RecaptchaInterface {

	/**
	 * Если значение не будет установлено в конфиге, то вернет false
	 * @var string|false Полный урл к сервису recaptcha
	 */
	public string|false $fullUrl = false;

	/**
	 * @var array Массив с секретными ключами (для Android и iOS)
	 */
	public array $keys = [];

	/**
	 * @inheritDoc
	 */
	public function init():void {
		$this->fullUrl = ArrayHelper::getValue(Yii::$app->modules, "recaptcha.fullUrl");
		if (empty($this->fullUrl)) {
			throw new InvalidConfigException('Не задан адрес API для проверки recaptcha');
		}

		$this->keys['ios'] = ArrayHelper::getValue(Yii::$app->modules, "recaptcha.keys.ios");
		$this->keys['android'] = ArrayHelper::getValue(Yii::$app->modules, "recaptcha.keys.android");
		$this->keys['web'] = ArrayHelper::getValue(Yii::$app->modules, "recaptcha.keys.web");
	}

	/**
	 * @param array $data
	 * @return Response
	 * @throws HttpClientException
	 * @throws InvalidConfigException
	 */
	private function doRequest(array $data):Response {
		$client = new Client([
			'transport' => CurlTransport::class
		]);
		$request = $client->createRequest();
		$request->method = 'POST';

		$request->addOptions([
			'sslVerifyPeer' => false
		]);

		$request->headers = [
			'accept' => 'text/plain',
			'Content-Type' => 'multipart/form-data'
		];

		$request->fullUrl = $this->fullUrl;
		$request->data = $this->createData($data);

		return $request->send();
	}

	/**
	 * @param array $data
	 * @return array
	 * @throws InvalidConfigException
	 */
	private function createData(array $data):array {
		$secret = ArrayHelper::getValue($this->keys, $data['os']);
		if (empty($secret)) {
			throw new InvalidConfigException("Не задан секретный ключ для {$data['os']}");
		}
		return [
			'secret' => $secret,
			'response' => $data['token']
		];
	}

	/**
	 * @inheritDoc
	 */
	public function checkRecaptcha(array $data):array {
		$response = (new RecaptchaService())->doRequest($data);
		return ResponseHandler::handle($response);
	}

}
