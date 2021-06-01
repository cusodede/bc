<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\permissions\traits\ControllerPermissionsTrait;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use pozitronik\filestorage\models\FileStorage;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use Throwable;

/**
 * Class AttachesController
 * @package app\controllers
 */
class UploadsController extends Controller {
	use ControllerPermissionsTrait;

	/**
	 * @inheritdoc
	 */
	public function behaviors():array {
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => ['download'],
						'allow' => true,
						'roles' => ['@']
					]
				]
			]
		];
	}

	/**
	 * Отдаёт загруженные файлы по ID
	 * @param null|int $id
	 * @return Response
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 */
	public function actionDownload(?int $id = null):Response {
		if (!$id) {
			return $this->redirect(Yii::$app->request->referrer?:Yii::$app->homeUrl);
		}

		$uploadedFile = FileStorage::findOne($id);
		if (null === $uploadedFile) {
			throw new NotFoundHttpException('Не найдена запись о файле');
		}

		if ($uploadedFile->deleted) {
			throw new NotFoundHttpException('Данный файл был удалён');
		}

		if (!file_exists($uploadedFile->path)) {
			throw new NotFoundHttpException('Файл не найден');
		}

		return Yii::$app->response->sendFile($uploadedFile->path);
	}
}
