<?php
declare(strict_types = 1);

namespace app\controllers;

use yii\base\Response;
use yii\web\Controller;

/**
 * Class ServiceController
 */
class ServiceController extends Controller {
//	todo: action list generator in trait
//	todo: url-generation like ServiceController::url(string $actionName, array $parameters = []])

	/**
	 * @return string|Response
	 */
	public function actionIndex() {
		return $this->render('index');
	}

}
