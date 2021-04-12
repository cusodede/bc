<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\users\Users;
use app\models\sys\users\UsersSearch;
use pozitronik\core\traits\ControllerTrait;
use pozitronik\sys_exceptions\models\LoggedException;
use Throwable;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UsersController
 */
class UsersController extends Controller {
	use ControllerTrait;

	/**
	 * Основной список пользователей
	 * @return string
	 * @throws Throwable
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new UsersSearch();
		$allowedGroups = [];

		$dataProvider = $searchModel->search($params, $allowedGroups);

		return $this->render('index', compact('searchModel', 'dataProvider'));
	}

	/**
	 * @param int $id
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionEdit(int $id) {
		if (null === $user = Users::findOne($id)) {
			throw new LoggedException(new NotFoundHttpException());
		}

		if ($user->updateModelFromPost()) return $this->redirect('index');

		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('modal/edit', [
				'model' => $user
			]);
		}
		return $this->render('edit', [
			'model' => $user
		]);
	}

	/**
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionCreate() {
		$user = new Users();
		if ($user->createModelFromPost()) {
			return $this->redirect('index');
		}
		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('modal/create', [
				'model' => $user
			]);
		}
		return $this->render('create', [
			'model' => $user
		]);
	}

	/**
	 * @param int $id
	 * @return Response
	 * @throws Throwable
	 */
	public function actionDelete(int $id):Response {
		if (null === $user = Users::findOne($id)) {
			throw new LoggedException(new NotFoundHttpException());
		}
		$user->safeDelete();
		return $this->redirect('index');
	}

	/**
	 * Профиль пользователя
	 * @param int $id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionProfile(int $id):?string {
		if (null === $user = Users::findOne($id)) {
			throw new LoggedException(new NotFoundHttpException());
		}
		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('modal/profile', [
				'model' => $user
			]);
		}
		return $this->render('profile', [
			'model' => $user
		]);
	}

	/**
	 * @param int $id
	 * @return string|Response
	 * @throws LoggedException
	 */
	public function actionUpdatePassword(int $id) {
		if (null === $user = Users::findOne($id)) {
			throw new LoggedException(new NotFoundHttpException());
		}
		if ($user->updateModelFromPost()) {
			return $this->redirect(['profile', 'id' => $user->id]);
		}
		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('modal/update-password', [
				'model' => $user
			]);
		}
		return $this->render('update-password', [
			'model' => $user
		]);
	}

}
