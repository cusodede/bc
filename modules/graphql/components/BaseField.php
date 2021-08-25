<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use app\components\helpers\ArrayHelper;
use app\modules\graphql\interfaces\ResolveInterface;
use app\modules\graphql\traits\BaseObjectTrait;
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
	use BaseObjectTrait;

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

	/**
	 * Унификация resolve для справочников.
	 * @param array $enumData
	 * @param int|null $attributeId
	 * @return array[]|null
	 * @throws Throwable
	 */
	public static function enumResolve(array $enumData, ?int $attributeId): ?array
	{
		if (null === $attributeId) {
			return array_map(
				static fn(string $name, int $id): array => compact('id', 'name'),
				$enumData,
				array_keys($enumData)
			);
		}
		$attributeName = ArrayHelper::getValue($enumData, $attributeId);
		return null !== $attributeName ? [['id' => $attributeId, 'name' => $attributeName]] : null;
	}
}