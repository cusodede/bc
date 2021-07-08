<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query\extended\enum;

use app\models\products\EnumProductsTypes;
use app\modules\graphql\base\BaseQueryType;
use app\modules\graphql\data\EnumTypes;
use GraphQL\Type\Definition\Type;

/**
 * Class ProductTypesType
 * @package app\modules\graphql\schema\query\extended\enum
 */
final class ProductTypesType extends BaseQueryType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор типа продукта',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование типа продукта',
				],
			],
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getListOfType(): array
	{
		return [
			'type' => Type::listOf(EnumTypes::productTypesType()),
			'resolve' => fn($productType, array $args = []): ?array
				=> self::getListFromEnum(EnumProductsTypes::mapData()),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getOneOfType(): array
	{
		return [
			'type' => EnumTypes::productTypesType(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'resolve' => fn($productType, array $args = []): ?array
				=> self::getOneFromEnum(EnumProductsTypes::mapData(), $args)
		];
	}
}
