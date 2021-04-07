<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\permissions\PermissionsCollectionsSearch;
use pozitronik\core\traits\ControllerTrait;
use Yii;
use yii\web\Controller;

/**
 * Class PermissionsCollectionsController
 */
class PermissionsCollectionsController extends Controller {
	use ControllerTrait;

	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new PermissionsCollectionsSearch();

		$dataProvider = $searchModel->search($params);

		return $this->render('index', compact('searchModel', 'dataProvider'));
	}
}