<?php
declare(strict_types = 1);

namespace app\modules\dol\models;

use Exception;
use simialbi\yii2\rest\ActiveRecord;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception as HttpClientException;
use yii\httpclient\Response;

/**
 * Class DolAPI
 * @property-read null|bool $success Last operation response status
 * @property-read string $errorMessage Last operation error message
 */
class DolAPI extends ActiveRecord {
	public string $baseUrl = "https://dolfront.beelinetst.ru/api/";

	public const METHOD_SMS_LOGON = 'auth/sms-logon';
	public const METHOD_CONFIRM_SMS_LOGON = 'auth/confirm-sms-logon';
	public const METHOD_REFRESH = 'v2/auth/refresh';

	/**
	 * @var bool|null
	 */
	public ?bool $success = null;

	/**
	 * @var string
	 */
	public string $errorMessage = '';

	/**
	 * @param string $url
	 * @param array $data
	 * @return Response
	 * @throws HttpClientException
	 * @throws InvalidConfigException
	 */
	private static function doRequest(string $url, array $data):Response {
		$client = new Client([
			'transport' => CurlTransport::class
		]);
		$request = $client->createRequest();
		$request->method = 'POST';
		$request->headers = [
			'accept' => 'application/json',
			'Content-Type' => 'application/json'
		];
		$request->fullUrl = $url;
		$request->data = json_encode($data);
		return $request->send();
	}

	/**
	 * @param string $answer
	 * @return array
	 * @throws Exception
	 */
	private function parseAnswer(string $answer):array {
		$this->success = false;
		if (null === $result = json_decode($answer, true, 512, JSON_OBJECT_AS_ARRAY)) {
			$this->errorMessage = 'Ошибка запроса к DOL API';
			return [];
		}
		$this->success = ArrayHelper::getValue($result, 'success', $this->success);
		$this->errorMessage = ArrayHelper::getValue($result, 'success', $this->success);
		return $result;
	}

	/**
	 * @param string $phoneAsLogin
	 * @return array
	 * @throws HttpClientException
	 * @throws InvalidConfigException
	 */
	public function smsLogon(string $phoneAsLogin):array {
		$response = self::doRequest($this->baseUrl.self::METHOD_SMS_LOGON, [
			'phoneAsLogin' => $phoneAsLogin
		]);
		return $this->parseAnswer($response->content);
	}

	/**
	 * @param string $phoneAsLogin
	 * @param string $code
	 * @return array
	 * @throws HttpClientException
	 * @throws InvalidConfigException
	 */
	public function confirmSmsLogon(string $phoneAsLogin, string $code):array {
		$response = self::doRequest($this->baseUrl.self::METHOD_CONFIRM_SMS_LOGON, compact('phoneAsLogin', 'code'));
		return $this->parseAnswer($response->content);
	}

}