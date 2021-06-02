<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\permissions\traits\ControllerPermissionsTrait;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use pozitronik\filestorage\models\FileStorage;
use yii\web\Controller;
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
	 * Отдаёт загруженный файлы по ID
	 * @param null|int $id
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 */
	public function actionDownload(?int $id = null):void {
		$fileStorage = FileStorage::findModel($id, new NotFoundHttpException('Не найдена запись о файле'));

		if (null !== $fileStorage) {
			if ($fileStorage->deleted) {
				throw new NotFoundHttpException('Данный файл был удалён');
			}

			if (!file_exists($fileStorage->path)) {
				throw new NotFoundHttpException('Файл не найден');
			}

			$fileStorage->download();
		}
	}
}
