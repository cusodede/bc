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
			'resolve' => fn($root, $args) => $args
		];
	}

	/**
	 * Унификация resolve для справочников.
	 * @param array $enumData
	 * @param int|null $attributeId
	 * @return array|null
	 * @throws Throwable
	 */
	public static function enumResolve(array $enumData, ?int $attributeId): ?array
	{
		$condition = null === ($name = ArrayHelper::getValue($enumData, $attributeId));
		return $condition ? null : ['id' => $attributeId, 'name' => $name];
	}
}