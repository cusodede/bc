<?php
declare(strict_types = 1);

namespace app\models\common;

use Exception;
use yii\helpers\ArrayHelper;

/**
 * Trait EnumInterface
 * @package app\models\common
 */
trait EnumTrait
{
	/**
	 * Получение значения по типу.
	 * @param $case
	 * @return mixed
	 * @throws Exception
	 */
	public static function getScalar($case): mixed
	{
		return ArrayHelper::getValue(static::mapData(), $case);
	}

	/**
	 * Массив соответствий типов и значений.
	 * @return array
	 */
	abstract public static function mapData(): array;
}