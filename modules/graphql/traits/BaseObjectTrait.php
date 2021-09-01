<?php
declare(strict_types = 1);

namespace app\modules\graphql\traits;

use yii\helpers\ArrayHelper;
use Throwable;

/**
 * Trait BaseObjectTrait
 * @package app\modules\graphql\traits
 */
trait BaseObjectTrait
{
	/**
	 * @var string[] Массив подгруженных классов.
	 */
	private static array $_fieldsMap = [];

	/**
	 * Мы не можем инстанцировать классы полей вне скоупа FieldDefinition, поскольку FieldDefinition::__construct()
	 * является protected. Этот метод позволяет элегантно справиться с задачей.
	 *
	 * @return static
	 * @throws Throwable
	 */
	public static function field(): static
	{
		if (null === ArrayHelper::getValue(self::$_fieldsMap, static::class)) {
			self::$_fieldsMap[static::class] = new static();
		}
		return self::$_fieldsMap[static::class];
	}
}