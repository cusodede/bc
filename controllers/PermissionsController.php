<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\permissions\Permissions;
use app\models\sys\permissions\PermissionsSearch;
use kartik\grid\EditableColumnAction;
use pozitronik\core\traits\ControllerTrait;
use pozitronik\helpers\ArrayHelper;
use pozitronik\sys_exceptions\models\LoggedException;
use Throwable;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class PermissionsController
 */
class PermissionsController extends Controller {
	use ControllerTrait;

	/**
	 * @inheritDoc
	 */
	public function actions():array {
		$defaultEditableActionConfig = [
			'class' => EditableColumnAction::class,
			'modelClass' => Permissions::class,
			'showModelErrors' => true,
		];
		/*@see https://webtips.krajee.com/setup-editable-column-grid-view-manipulate-records/*/
		return ArrayHelper::merge(parent::actions(), [
			/**
			 * Можно назначить один экшен на все поля, которым не требуется специализированный обработчик,
			 * данные всё равно грузятся так, будто постится полная форма.
			 * @see \kartik\grid\EditableColumnAction::validateEditable()
			 */
			'editDefault' => $defaultEditableActionConfig,
			'editAction' => [
				'class' => EditableColumnAction::class,
				'modelClass' => Permissions::class,
				'showModelErrors' => true,
//				'outputValue' => function(Permissions $model, string $attribute, int $key, int $index) {
//					if (in_array($attribute, Permissions::ALLOWED_EMPTY_PARAMS)) {
//						return "*";
//					}
//					return '';
//				},
			]
		]);
	}

	/**
	 * @return string
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new PermissionsSearch();

		$dataProvider = $searchModel->search($params);

		return $this->render('index', compact('searchModel', 'dataProvider'));
	}

	/**
	 * @param int $id
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionEdit(int $id) {
		if (null === $permission = Permissions::findOne($id)) {
			throw new LoggedException(new NotFoundHttpException());
		}
		if ($permission->updateModel(Yii::$app->request->post($permission->formName()))) {
			return $this->redirect('index');
		}
		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('modal/edit', [
				'model' => $permission
			]);
		}
		return $this->render('edit', [
			'model' => $permission
		]);
	}
}