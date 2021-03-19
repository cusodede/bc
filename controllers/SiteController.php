<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\site\LoginForm;
use app\models\sys\users\CurrentUser;
use pozitronik\helpers\ArrayHelper;
use Yii;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller {

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
	 */
	public function actionLogin() {
		$this->layout = 'login';
		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->doLogin()) {
			return CurrentUser::goHome();
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
	 * @return string|Response
	 */
	public function actionIndex() {
		return CurrentUser::isGuest()?$this->redirect(ArrayHelper::getValue(Yii::$app->params, 'user.loginpage', ['site/login'])):CurrentUser::goHome();
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
