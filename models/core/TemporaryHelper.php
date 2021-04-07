<?php /** @noinspection EmptyClassInspection */
declare(strict_types = 1);

namespace app\models\core;

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
}