<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\permissions\PermissionsSearch;
use pozitronik\core\traits\ControllerTrait;
use Yii;
use yii\web\Controller;

/**
 * Class PermissionsController
 */
class PermissionsController extends Controller {
	use ControllerTrait;

	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new PermissionsSearch();

		$dataProvider = $searchModel->search($params);

		return $this->render('index', compact('searchModel', 'dataProvider'));
	}
}