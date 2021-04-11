<?php
declare(strict_types = 1);

namespace app\controllers\rest;

use app\models\rest\Users;
use simialbi\yii2\rest\RestDataProvider;
use Yii;
use yii\web\Controller;

/**
 * Class UsersController
 */
class UsersController extends Controller {

	/**
	 * @return string
	 */
	public function actionIndex() {
		$params = Yii::$app->request->queryParams;
		$t = Users::find()->all();

		$dataProvider = new RestDataProvider(['query' => Users::find()]);

		return $this->render('index', compact('dataProvider'));
	}

}