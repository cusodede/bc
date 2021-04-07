<?php /** @noinspection EmptyClassInspection */
declare(strict_types = 1);

namespace app\models\core;

use pozitronik\core\helpers\ControllerHelper;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\web\Controller;

/**
 * Class TemporaryHelper
 * Если понадобилось быстро сделать хелперную функцию, которую пока непонятно куда - пихаем сюда, потом рефакторим
 */
class TemporaryHelper {

	public const VERBS = [
		'GET' => 'GET',
		'HEAD' => 'HEAD',
		'POST' => 'POST',
		'PUT' => 'PUT',
		'PATCH' => 'PATCH',
		'DELETE' => 'DELETE'
	];

	/**
	 * @return string[]
	 * @throws Throwable
	 */
	public static function GetControllersList():array {
		return ArrayHelper::map(ControllerHelper::GetControllersList('@app/controllers', null, [Controller::class]), 'id', 'id');
	}
}