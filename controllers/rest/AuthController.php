<?php
declare(strict_types = 1);

namespace app\controllers\rest;

use yii\base\Model;
use yii\httpclient\Client;

/**
 * Class AuthController
 */
class AuthController extends Model {

	public function authenticate($username, $password) {
		$client = new Client();
		$response = $client->createRequest()
			->setMethod('POST')
			->setUrl('http::/bc/api/login')
			->setData(['username' => $username, 'password' => $password])
			->send();
		if ($response->isOk) {
			$this->_token = $response->data['token'];
		}
	}
}