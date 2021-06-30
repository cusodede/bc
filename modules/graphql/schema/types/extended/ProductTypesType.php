<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\extended;

use app\models\products\EnumProductsTypes;
use app\modules\graphql\schema\common\Types;
use app\modules\graphql\schema\types\TypeTrait;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class ProductTypesType
 * @package app\modules\graphql\schema\types\extended
 */
final class ProductTypesType extends ObjectType
{
	use TypeTrait;

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
			'type' => Type::listOf(Types::productTypesType()),
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
			'type' => Types::productTypesType(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'resolve' => fn($productType, array $args = []): ?array
				=> self::getOneFromEnum(EnumProductsTypes::mapData(), $args)
		];
	}
}