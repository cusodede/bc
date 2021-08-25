<?php
declare(strict_types = 1);

namespace app\modules\graphql\base;

use GraphQL\Type\Definition\ObjectType;
use yii\helpers\ArrayHelper;
use Exception;

/**
 * Class BaseQueryType
 * @package app\modules\graphql\base
 */
abstract class BaseQueryType extends ObjectType
{
	/**
	 * Список сущностей
	 * @return array
	 */
	abstract public static function getListOfType(): array;

	/**
	 * Одна сущность
	 * @return array
	 */
	abstract public static function getOneOfType(): array;

	/**
	 * Преобразовывает наши Enum списки в массив вида:
	 * [
	 *        ['id' => 1, 'name' => 'value1']
	 *        ['id' => 2, 'name' => 'value2']
	 * ]
	 * @param array $enumData
	 * @return array
	 */
	public static function getListFromEnum(array $enumData): array
	{
		return array_map(
			static fn(string $name, int $id): array => compact('id', 'name'),
			$enumData,
			array_keys($enumData)
		);
	}

	/**
	 * Выборка по ключу из Enum для GraphQl
	 * @param array $enumData
	 * @param $args
	 * @return array|null
	 * @throws Exception
	 */
	public static function getOneFromEnum(array $enumData, $args): ?array
	{
		$id   = ArrayHelper::getValue($args, 'id', 0);
		$name = ArrayHelper::getValue($enumData, $id, false);
		return $name ? compact('id', 'name') : null;
	}
}
