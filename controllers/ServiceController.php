<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\Service;
use yii\base\Response;
use yii\web\Controller;

/**
 * Class ServiceController
 */
class ServiceController extends Controller {

	/**
	 * @return string|Response
	 */
	public function actionIndex() {
		return $this->render('index');
	}

	/**
	 * @return string
	 */
	public function actionReset():string {
//		Yii::$app->user->logout();
		return $this->render('reset', [
			'result' => Service::ResetDB()
		]);

	}
}
