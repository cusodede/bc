<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\sys\users\Users;
use app\models\sys\users\UsersSearch;
use pozitronik\sys_exceptions\models\LoggedException;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\filters\ContentNegotiator;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UsersController
 */
class UsersController extends DefaultController {

	/**
	 * Поисковая модель пользователя
	 * @var string
	 */
	public string $modelSearchClass = UsersSearch::class;

	/**
	 * Модель пользователя
	 * @var string
	 */
	public string $modelClass = Users::class;

	public bool $enablePrototypeMenu = false;

	/**
	 * Переопределим базовую директорию views
	 * @return string
	 */
	public function getViewPath():string {
		return '@app/views/users';
	}

	public function behaviors():array {
		return ArrayHelper::merge(parent::behaviors(), [
			[
				'class' => ContentNegotiator::class,
				'only' => ['logo-upload'],
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
				],
			]
		]);
	}

	/**
	 * Профиль пользователя
	 * @param null|int $id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionProfile(?int $id = null):?string {
		$user = (null === $id)?Users::Current():Users::findOne($id);
		if (null === $user) {
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
	 * Загрузка фото профиля
	 * @return array
	 * @throws LoggedException
	 * @throws Throwable
	 */
	public function actionLogoUpload():array {
		try {
			Users::Current()->uploadAttribute('avatar');
		} catch (Throwable $t) {
			throw new LoggedException($t);
		}

		return [];
	}

	/**
	 * @param int|null $id
	 * @throws LoggedException
	 */
	public function actionLogoGet(?int $id = null):void {
		$user = null === $id?Users::Current():Users::findOne($id);
		if (null === $user) {
			throw new LoggedException(new NotFoundHttpException());
		}
		if (null === $user->fileAvatar) {
			Yii::$app->response->sendFile(Yii::getAlias(Users::DEFAULT_AVATAR_ALIAS_PATH));
		} else {
			$user->fileAvatar->download();
		}
	}

	/**
	 * Авторизоваться под другим пользователем
	 *
	 * @param int $userId
	 * @return Response
	 * @throws Throwable
	 */
	public function actionLoginAsAnotherUser(int $userId):Response {
		Yii::$app->user->loginAsAnotherUser($userId);
		return $this->redirect(Yii::$app->homeUrl);
	}

	/**
	 * Вернуться в свою учетную запись
	 *
	 * @return Response
	 */
	public function actionLoginBack():Response {
		Yii::$app->user->loginBackToOriginUser();
		return $this->redirect(Yii::$app->homeUrl);
	}

}
