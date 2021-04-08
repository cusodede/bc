<?php
declare(strict_types = 1);

namespace app\controllers;

use pozitronik\core\traits\ControllerTrait;
use yii\web\Controller;

/**
 * Class ServiceController
 */
class ServiceController extends Controller {
	use ControllerTrait;
	
	/**
	 * @return string
	 */
	public function actionIndex():string {
		return $this->render('index');
	}

}
