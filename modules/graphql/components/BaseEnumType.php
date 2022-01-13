<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use app\components\helpers\ArrayHelper;
use GraphQL\Type\Definition\EnumType;
use Throwable;

/**
 * Class BaseEnumType
 * Заготовка под перечислимые типы
 */
class BaseEnumType extends EnumType {

	/**
	 * @var string[] Массив подгруженных полей
	 */
	private static array $_fieldsMap = [];

	/**
	 * @return static
	 * @throws Throwable
	 */
	public static function field():static {
		if (null === ArrayHelper::getValue(self::$_fieldsMap, static::class)) {
			/** @noinspection PhpParamsInspection Временно, как только появятся вызовы - оно уйдёт */
			self::$_fieldsMap[static::class] = new static();
		}
		return self::$_fieldsMap[static::class];
	}
}