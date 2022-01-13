<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use app\components\helpers\ArrayHelper;
use app\modules\graphql\interfaces\ResolveInterface;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ResolveInfo;
use Throwable;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use yii\web\ForbiddenHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * Class BaseField
 * @property bool $requireAuthentication Требовать аутентификации для выполнения запроса
 */
abstract class BaseField extends FieldDefinition implements ResolveInterface {

	/**
	 * @var bool Требовать аутентификации для выполнения запроса
	 */
	public bool $requireAuthentication = true;

	/**
	 * @var string[] Массив подгруженных полей
	 */
	private static array $_fieldsMap = [];

	/**
	 * @inheritDoc
	 */
	protected function __construct(array $config) {
		if (!isset($config['resolve'])) {/*проверять существование метода не нужно, он явно обозначен интерфейсом*/
			$config['resolve'] = function(mixed $root, array $args, mixed $context, ResolveInfo $resolveInfo) {
				if ($this->requireAuthentication && null === AuthHelper::getAuthUser()) throw new Error('Require authentication');
				return static::resolve(new ResolveParameter(compact('root', 'args', 'context', 'resolveInfo')));
			};
		}

		parent::__construct($config);
	}

	/**
	 * Мы не можем инстанцировать классы полей вне скоупа FieldDefinition, поскольку FieldDefinition::__construct()
	 * является protected. Этот метод позволяет элегантно справиться с задачей.
	 *
	 * @return static
	 * @throws Throwable
	 */
	public static function field():static {
		if (null === ArrayHelper::getValue(self::$_fieldsMap, static::class)) {
			self::$_fieldsMap[static::class] = new static();
		}
		return self::$_fieldsMap[static::class];
	}

	/**
	 * Вытаскивает из аргументов значение фильтра
	 * @param array $args
	 * @param string $field_name
	 * @param mixed $default
	 * @return mixed
	 * @throws Throwable
	 */
	public static function filterValue(array $args, string $field_name, mixed $default = null):mixed {
		return ArrayHelper::getValue($args, "filters.$field_name", $default);
	}

	/**
	 * @return int
	 * @throws Throwable
	 * @throws InvalidConfigException
	 * @throws NotInstantiableException
	 * @throws ForbiddenHttpException
	 * @throws UnauthorizedHttpException
	 */
	public static function userId():int {
		return AuthHelper::authenticate()->id;
	}

}