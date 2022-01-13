<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\sys\permissions\filters\PermissionFilter;
use app\models\sys\permissions\traits\ControllerPermissionsTrait;
use yii\web\Controller;

/**
 * Class ServiceController
 */
class ServiceController extends Controller {
	use ControllerPermissionsTrait;

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return [
			'access' => [
				'class' => PermissionFilter::class
			]
		];
	}

	/**
	 * @return string
	 */
	public function actionIndex():string {
		return $this->render('index');
	}

}
