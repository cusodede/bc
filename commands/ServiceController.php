<?php
declare(strict_types = 1);

namespace app\commands;

use app\models\core\Service;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class ServiceController
 * @package app\commands
 */
class ServiceController extends Controller {

	/**
	 * Инициализирует приложение
	 * @return void
	 */
	public function actionInit():void {
		Console::output(Console::renderColoredString(Service::ResetDB()?"%gУспешно%n":"%rСбой%n"));
	}
}