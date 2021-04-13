<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\site\LoginForm;
use app\models\site\UpdatePasswordForm;
use app\models\sys\users\Users;
use pozitronik\core\traits\ControllerTrait;
use pozitronik\helpers\ArrayHelper;
use pozitronik\sys_exceptions\models\LoggedException;
use Throwable;
use Yii;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

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
			if ($model->user->is_pwd_outdated) {
				return $this->redirect('update-password');
			}
			return $this->redirect(Yii::$app->homeUrl);
		}
		return $this->render('login', [
			'login' => $model
		]);
	}

	/**
	 * logout
	 */
	public function actionLogout():void {
		Yii::$app->user->logout();
		$this->redirect('index');
	}

	/**
	 * На момент входа в экшен пользователь должен быть авторизован.
	 * Если при этом у него протухший пароль (перекинуло из actionLogin()), то вызывается форма смены пароля,
	 *
	 * Пользователь остаётся авторизован (это нужно для проброса данных смены пароля), но его не пускает
	 * в основном шаблоне приложения
	 *
	 * @return string|Response
	 * @throws LoggedException
	 */
	public function actionUpdatePassword() {
		$this->layout = 'login';
		/** @var Users|null $loggedUser */
		if (null === $loggedUser = Yii::$app->user->identity) {
			throw new LoggedException(new UnauthorizedHttpException('Пользователь не авторизован'));
		}

		$updatePasswordModel = new UpdatePasswordForm(['user' => $loggedUser]);
		if ($updatePasswordModel->load(Yii::$app->request->post()) && $updatePasswordModel->doUpdate()) {
			return $this->redirect(Yii::$app->homeUrl);
		}
		return $this->render('update-password', [
			'model' => $updatePasswordModel
		]);
	}

	/**
	 * @return Response
	 * @throws Throwable
	 */
	public function actionIndex():Response {
		return (Yii::$app->user->isGuest || ArrayHelper::getValue(Yii::$app->user->identity, 'is_pwd_outdated', false))?$this->redirect(ArrayHelper::getValue(Yii::$app->params, 'user.loginpage', ['site/login'])):$this->redirect(Yii::$app->homeUrl);
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
