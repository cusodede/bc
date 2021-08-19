<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use app\components\helpers\ArrayHelper;
use GraphQL\Type\Definition\ObjectType;
use Throwable;

/**
 * Class BaseTypes
 */
abstract class BaseObjectType extends ObjectType
{

	/**
	 * @var string[] Массив подгруженных типов
	 */
	private static array $_typesMap = [];

	/**
	 * @return static
	 * @throws Throwable
	 */
	public static function type(): static
	{
		if (null === ArrayHelper::getValue(self::$_typesMap, static::class)) {
			self::$_typesMap[static::class] = new static();
		}
		return self::$_typesMap[static::class];
	}

	/**
	 * Просто шорткат для получения конфига корневой сущности.
	 * @return array
	 * @throws Throwable
	 */
	public static function root(): array
	{
		return [
			'type' => static::type(),
			'resolve' => function($root, $args) {
				return $args;
			}
		];
	}
}