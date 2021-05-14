<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\permissions\filters\PermissionFilter;
use pozitronik\core\traits\ControllerTrait;
use yii\web\Controller;

/**
 * Class HomeController
 * @package app\controllers
 */
class HomeController extends Controller {
	use ControllerTrait;

	public function behaviors(): array
	{
		return [
			'access' => [
				'class' => PermissionFilter::class
			]
		];
	}

	/**
	 * @return string
	 */
	public function actionHome():string {
		return $this->render('home');
	}
}
