<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\site\LoginForm;
use pozitronik\core\traits\ControllerTrait;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller {
	use ControllerTrait;

	/**
	 * @inheritdoc
	 */
	public function actions():array {
		return [
			'error' => [
				'class' => ErrorAction::class
			]
		];
	}

	/**
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionLogin() {
		$this->layout = 'login';
		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->doLogin()) {
			return $this->redirect(Yii::$app->homeUrl);
		}
		return $this->render('login', [
			'login' => $model
		]);
	}

	public function actionLogout():void {
		Yii::$app->user->logout();
		$this->redirect('index');
	}

	/**
	 * @return Response
	 * @throws Throwable
	 */
	public function actionIndex():Response {
		return Yii::$app->user->isGuest?$this->redirect(ArrayHelper::getValue(Yii::$app->params, 'user.loginpage', ['site/login'])):$this->redirect(Yii::$app->homeUrl);
	}

	/**
	 * @return string
	 */
	public function actionError():string {
		$exception = Yii::$app->errorHandler->exception;

		if (null !== $exception) {
			return $this->render('error', [
				'exception' => $exception
			]);
		}
		return "Status: {$exception->statusCode}";

	}

}
