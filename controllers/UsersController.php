<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\users\Users;
use app\models\sys\users\UsersSearch;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UsersController
 */
class UsersController extends Controller {

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
	 * Профиль пользователя
	 * @param int $id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionProfile(int $id):?string {
		if (null === $user = Users::findModel($id, new NotFoundHttpException())) return null;
		return $this->render('profile', [
			'model' => $user
		]);
	}

	/**
	 * Редактирование пользователя
	 * @param int $id
	 * @return string|Response|null
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function actionUpdate(int $id) {
		if (null === $user = Users::findModel($id, new NotFoundHttpException())) return null;
		if (Yii::$app->request->isPost && $user->load(Yii::$app->request->post()) && $user->save()) {
			return $this->redirect(['users/profile', 'id' => $id]);
		}
		return $this->render('update', [
			'model' => $user
		]);

	}

	/**
	 * @param int $id
	 * @return Response
	 * @throws Throwable
	 */
	public function actionDelete(int $id):Response {
		if (null !== $user = Users::findModel($id, new NotFoundHttpException())) $user->safeDelete();
		return $this->redirect('index');
	}

}
