<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\permissions\active_record\PermissionsCollections;
use app\models\sys\permissions\PermissionsCollectionsSearch;
use pozitronik\core\traits\ControllerTrait;
use Throwable;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class PermissionsCollectionsController
 */
class PermissionsCollectionsController extends Controller {
	use ControllerTrait;

	/**
	 * @return string
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new PermissionsCollectionsSearch();

		$dataProvider = $searchModel->search($params);

		return $this->render('index', compact('searchModel', 'dataProvider'));
	}

	/**
	 * @param int $id
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionEdit(int $id) {
		if (null === $permission = PermissionsCollections::findModel($id, new NotFoundHttpException())) return null;
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