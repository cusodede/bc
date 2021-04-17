<?php
declare(strict_types = 1);

namespace app\controllers\api;

use app\models\core\Status;
use app\models\site\LoginForm;
use Yii;
use yii\rest\Controller;

/**
 * Class LoginController
 */
class LoginController extends Controller {

	/**
	 * @inheritDoc
	 */
	protected function verbs():array {
		return [
			'index' => ['POST'],
		];
	}

	/**
	 * @return array
	 */
	public function index():array {
		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post())) {
			return [
				'status' => Status::STATUS_BAD_REQUEST,
				'message' => "Need username and password.",
				'data' => ''
			];
		}
		if (null === $token = $model->getToken(Yii::$app->request->userIP, Yii::$app->request->userAgent)) {
			return [
				'status' => Status::STATUS_UNAUTHORIZED,
				'message' => array_shift($model->errors),
				'data' => ''
			];
		}
		return [
			'status' => Status::STATUS_FOUND,
			'message' => 'Login Succeed, save your token',
			'data' => [
				'id' => $model->user->username,
				'token' => $token->auth_token,
				'valid' => $token->valid
			]
		];
	}
}