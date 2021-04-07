<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\permissions\PermissionsSearch;
use kartik\grid\EditableColumnAction;
use pozitronik\core\traits\ControllerTrait;
use pozitronik\helpers\ArrayHelper;
use Yii;
use yii\web\Controller;

/**
 * Class PermissionsController
 */
class PermissionsController extends Controller {
	use ControllerTrait;

	/**
	 * @inheritDoc
	 */
	public function actions() {
		$defaultEditableActionConfig = [
			'class' => EditableColumnAction::class,
			'modelClass' => PermissionsSearch::class,
			'showModelErrors' => true,
		];

		return ArrayHelper::merge(parent::actions(), [
			/**
			 * Можно назначить один экшен на все поля, которым не требуется специализированный обработчик,
			 * данные всё равно грузятся так, будто постится полная форма.
			 * @see \kartik\grid\EditableColumnAction::validateEditable()
			 */
			'editDefault' => $defaultEditableActionConfig,
			'editAction' => [
				'class' => EditableColumnAction::class,
				'modelClass' => PermissionsSearch::class,
				'showModelErrors' => true,
//				'outputValue' => function(PermissionsSearch $model, string $attribute, int $key, int $index) {
//					if ('' === $model->$attribute && null !== $model->controller) {
//						return 'Все';
//					}
//					return '';
//				},
			]
		]);
	}

	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new PermissionsSearch();

		$dataProvider = $searchModel->search($params);

		return $this->render('index', compact('searchModel', 'dataProvider'));
	}
}