<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use ReflectionClass;

/**
 * Trait TypeTrait
 * @package app\modules\graphql\schema\types
 */
trait TypeTrait
{
	/**
	 * Преобразовывает наши Enum списки в массив вида:
	 * [
	 * 		['id' => 1, 'name' => 'value1']
	 * 		['id' => 2, 'name' => 'value2']
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
		$id = ArrayHelper::getValue($args, 'id', 0);
		$name = ArrayHelper::getValue($enumData, $id, false);
		return $name ? compact('id', 'name') : null;
	}

	/**
	 * Трансформация параметров для ActiveDataProvider
	 * @param Model $searchModel
	 * @param $args
	 * @return array
	 * @throws Exception
	 */
	public static function transformToSearchModelParams(Model $searchModel, $args): array
	{
		return [(new ReflectionClass($searchModel))->getShortName() => $args];
	}

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
}