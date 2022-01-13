<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use GraphQL\Type\Definition\ObjectType;
use Throwable;

/**
 * Class BaseTypes
 */
abstract class BaseObjectType extends ObjectType {

	/**
	 * @return static
	 * @throws Throwable
	 */
	public static function type():static {
		return TypeLoader::type(static::class);
	}

	/**
	 * Просто шорткат для получения конфига корневой сущности
	 * @return array
	 * @throws Throwable
	 */
	public static function root():array {
		return [
			'type' => static::type(),
			'resolve' => function($root, $args) {
				return $args;
			}
		];
	}
}