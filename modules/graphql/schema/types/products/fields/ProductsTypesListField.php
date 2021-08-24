<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products\fields;

use app\models\products\EnumProductsTypes;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\products\inputs\ProductsTypesFilterInput;
use app\modules\graphql\schema\types\products\ProductTypesType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Throwable;

/**
 * Class ProductsTypesListField
 * @package app\modules\graphql\schema\types\products\fields
 */
class ProductsTypesListField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'productTypesList',
			'type' => Type::listOf(ProductTypesType::type()),
			'description' => 'Список типов продуктов',
			'args' => [
				'filters' => [
					'type' => new ProductsTypesFilterInput(),
				],
			],
			'resolve' => fn(mixed $root, array $args, mixed $context, ResolveInfo $resolveInfo): ?array => static::resolve(
				$root, $args, $context, $resolveInfo
			)
		]);
	}

	/**
	 * @inheritdoc
	 * @throws Throwable
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): ?array
	{
		return static::enumResolve(EnumProductsTypes::mapData(), static::filterValue($args, 'id'));
	}
}