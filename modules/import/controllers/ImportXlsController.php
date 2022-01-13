<?php
declare(strict_types = 1);

namespace app\modules\import\controllers;

use app\models\sys\permissions\filters\PermissionFilter;
use app\models\sys\permissions\traits\ControllerPermissionsTrait;
use app\modules\import\helpers\ImportHelper;
use app\modules\import\models\ImportStatusAction;
use pozitronik\filestorage\models\FileStorage;
use pozitronik\helpers\ArrayHelper;
use pozitronik\traits\traits\ControllerTrait;
use Throwable;
use Yii;
use yii\web\Controller;

/**
 * Class ImportXlsController
 */
class ImportXlsController extends Controller {
	use ControllerPermissionsTrait;
	use ControllerTrait;

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return [
			'access' => [
				'class' => PermissionFilter::class
			]
		];
	}

	/**
	 * @inheritDoc
	 */
	public function actions():array {
		return ArrayHelper::merge(parent::actions(), [
			'import-status' => [
				'class' => ImportStatusAction::class
			]
		]);
	}

	/**
	 * @param $model
	 * @param string $job
	 * @return string
	 * @throws Throwable
	 */
	public function defaultAction($model, string $job):string {
		if (Yii::$app->request->isPost) {
			$model->uploadAttribute('loadXls');
			if (null !== $lastFileName = ArrayHelper::getValue($model->files(['loadXls']), 0)) {
				/** @var FileStorage $lastFileName */
				$importStatus = ImportHelper::createImportStatusRecord($model, $lastFileName->path);

				Yii::$app->queue_common->push(new $job([
					'filePath' => $lastFileName->path,
					'model' => $importStatus->model,
					'domain' => $importStatus->domain
				]));

				return $this->render('task-on-queue', [
					'action' => $this->action->id,
					'domain' => $importStatus->domain,
					'modelClass' => $importStatus->model
				]);
			}
		}
		return $this->render('load-xls', ['model' => $model]);
	}
}
