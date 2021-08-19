<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use app\components\helpers\ArrayHelper;
use app\modules\graphql\interfaces\ResolveInterface;
use GraphQL\Type\Definition\FieldDefinition;
use Throwable;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * Class BaseField
 */
abstract class BaseField extends FieldDefinition implements ResolveInterface
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

	/**
	 * Вытаскивает из аргументов значение фильтра.
	 * @param array $args
	 * @param string $fieldName
	 * @param mixed|null $default
	 * @return mixed
	 * @throws Throwable
	 */
	public static function filterValue(array $args, string $fieldName, mixed $default = null): mixed
	{
		return ArrayHelper::getValue($args, "filters.$fieldName", $default);
	}

	/**
	 * @return int
	 * @throws Throwable
	 * @throws InvalidConfigException
	 * @throws NotInstantiableException
	 * @throws ForbiddenHttpException
	 * @throws UnauthorizedHttpException
	 */
	public static function userId(): int
	{
		return AuthHelper::authenticate()->id;
	}
}