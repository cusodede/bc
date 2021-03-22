<?php
declare(strict_types = 1);

namespace app\controllers;

use pozitronik\core\traits\ControllerTrait;
use yii\base\Response;
use yii\web\Controller;

/**
 * Class ServiceController
 */
class ServiceController extends Controller {
	use ControllerTrait;
	
	/**
	 * @return string|Response
	 */
	public function actionIndex() {
		return $this->render('index');
	}

}
