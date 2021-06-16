<?php
declare(strict_types = 1);

namespace app\commands;

use app\models\core\Service;
use app\models\core\TemporaryHelper;
use app\models\sys\permissions\Permissions;
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

	/**
	 * Добавляет разрешения, описанные в файле конфигурации, в БД
	 */
	public function actionInitConfigPermissions():void {
		$configPermissions = Permissions::GetConfigurationPermissions();
		foreach ($configPermissions as $permissionAttributes) {
			$permission = new Permissions($permissionAttributes);
			Console::output(Console::renderColoredString($permission->save()?"%g{$permission->name}: добавлено%n":"%r{$permission->name}: пропущено (".TemporaryHelper::Errors2String($permission->errors).")%n"));
		}
	}
}