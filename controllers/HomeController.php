<?php
declare(strict_types = 1);

namespace app\controllers;

use yii\web\Controller;

/**
 * Class HomeController
 * @package app\controllers
 */
class HomeController extends Controller {

	/**
	 * @return string
	 */
	public function actionHome() {
		return $this->render('home');
	}


}
