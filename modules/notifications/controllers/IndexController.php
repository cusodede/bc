<?php
declare(strict_types = 1);

namespace app\modules\notifications\controllers;

use app\modules\notifications\models\handlers\EmailNotification;
use app\modules\notifications\models\Notification;
use yii\web\Controller;

/**
 * Class IndexController
 */
class IndexController extends Controller {

	/**
	 * @return void
	 */
	public function actionIndex():void {
		$notification = new Notification([
			'handler' => EmailNotification::class,
			'handlerParams' => [
				'from' => 'server@email',
				'to' => 'user@email',
				'subject' => 'experimental'
			]
		]);
		$notification->send();
	}

}