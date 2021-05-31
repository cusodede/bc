<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\users\Users;
use app\models\sys\users\UsersSearch;
use DomainException;
use pozitronik\core\traits\ControllerTrait;
use pozitronik\sys_exceptions\models\LoggedException;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UsersController
 */
class UsersController extends Controller {
	use ControllerTrait;

	public function behaviors() {
		return [
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'login-as-another-user'  => ['POST'],
				],
			],
		];
	}

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

		if ($user->updateModelFromPost()) {
			return $this->redirect('index');
		}

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
	 * @throws Throwable
	 * @throws Exception
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

	/**
	 * Авторизоваться под другим пользователем
	 *
	 * @return Response
	 */
	public function actionLoginAsAnotherUser() {
		try {
			$userId = (int) Yii::$app->request->post('userId');
			Yii::$app->user->loginAsAnotherUser($userId);
			Yii::$app->session->setFlash('success', 'Вы успешно авторизовались');
			return $this->redirect(['profile', 'id' => $userId]);
		} catch (DomainException $e) {
			Yii::$app->session->setFlash('error', 'Ошибка доступа');
			return $this->redirect(Url::toRoute(['users/index']));
		}
	}

	/**
	 * Вернуться в свою учетную запись
	 *
	 * @return Response
	 */
	public function actionLoginBack()
	{
		try {
			$originalId = Yii::$app->user->getOriginalUserId();
			Yii::$app->user->loginBackToOriginUser();
			Yii::$app->session->setFlash('success', 'Вы успешно вернулись обратно');
			return $this->redirect(['profile', 'id' => $originalId]);
		} catch (DomainException $e) {
			Yii::$app->session->setFlash('error', 'Ошибка доступа');
			return $this->redirect(Url::toRoute(['users/index']));
		}
	}

}
