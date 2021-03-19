<?php
declare(strict_types = 1);

namespace app\controllers;

use Yii;
use yii\web\Controller;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller {

	/**
	 * @return string
	 */
	public function actionIndex():string {
		return $this->render('index');
	}

	public function actionError():string {
		$exception = Yii::$app->errorHandler->exception;

		if (null !== $exception) {
			return $this->render('error', [
				'exception' => $exception
			]);
		}
		return "Status: {$exception->statusCode}";

	}

}
