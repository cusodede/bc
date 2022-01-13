<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use app\components\helpers\ArrayHelper;
use GraphQL\Type\Definition\Type;
use Throwable;

/**
 * Class TypeLoader
 * Фабрика загрузки типов данных
 */
class TypeLoader {

	/**
	 * @var string[] Массив подгруженных типов
	 */
	private static array $_typesMap = [];

	/**
	 * @param string $className
	 * @return Type
	 * @throws Throwable
	 */
	public static function type(string $className):Type {
		if (null === ArrayHelper::getValue(self::$_typesMap, $className)) {
			self::$_typesMap[$className] = new $className();
		}
		return self::$_typesMap[$className];
	}

}