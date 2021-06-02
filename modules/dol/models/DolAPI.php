<?php
declare(strict_types = 1);

namespace app\modules\dol\models;

use simialbi\yii2\rest\ActiveRecord;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception as HttpClientException;
use yii\httpclient\Response;

/**
 * Class DolAPI
 */
class DolAPI extends ActiveRecord {
	public string $baseUrl = "https://dolfront.beelinetst.ru/api/";

	public const METHOD_SMS_LOGON = 'auth/sms-logon';
	public const METHOD_CONFIRM_SMS_LOGON = 'auth/confirm-sms-logon';
	public const METHOD_REFRESH = 'v2/auth/refresh';


	/**
	 * @param string $method
	 * @param array $data
	 * @return Response
	 * @throws HTTPClientException
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
	 * @param string $phoneAsLogin
	 * @throws HttpClientException
	 */
	public function smsLogon(string $phoneAsLogin):array {
		$response = self::doRequest($this->baseUrl.self::METHOD_SMS_LOGON, [
			'phoneAsLogin' => $phoneAsLogin
		]);
		return json_decode($response->content, true, 512, JSON_OBJECT_AS_ARRAY);
	}


	public function confirmSmsLogon(string $phoneAsLogin, string $code):array {
		$response = self::doRequest($this->baseUrl.self::METHOD_CONFIRM_SMS_LOGON, [
			'phoneAsLogin' => $phoneAsLogin,
			'code' => $code
		]);
		return json_decode($response->content, true, 512, JSON_OBJECT_AS_ARRAY);
	}

}