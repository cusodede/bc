<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\managers\Managers;
use app\models\managers\ManagersSearch;
use app\models\sys\permissions\filters\PermissionFilter;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class ManagersController
 */
class ManagersController extends DefaultController {

	public string $modelClass = Managers::class;
	public string $modelSearchClass = ManagersSearch::class;

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return ArrayHelper::merge(parent::behaviors(), [
			'access' => [
				'class' => PermissionFilter::class
			]
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function getViewPath():string {
		return '@app/views/managers';
	}

	/**
	 * @inheritDoc
	 */
	public function actionCreate() {
		/** @var Managers $model */
		$model = $this->model;
		$model->scenario = $model::SCENARIO_CREATE;
		if (Yii::$app->request->post('ajax')) {
			return $this->asJson($model->validateModelFromPost());
		}
		$errors = [];
		$posting = $model->createModelFromPost($errors);
		if (true === $posting) {
			$model->createAccess();
			return $this->redirect('index');
		}
		/* Пришёл постинг, но есть ошибки */
		if ((false === $posting) && Yii::$app->request->isAjax) {
			return $this->asJson($errors);
		}

		/* Постинга не было */
		return (Yii::$app->request->isAjax)
			?$this->renderAjax('modal/create', ['model' => $model])
			:$this->render('create', ['model' => $model]);
	}
}