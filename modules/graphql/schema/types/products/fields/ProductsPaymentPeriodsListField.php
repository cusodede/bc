<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products\fields;

use app\models\products\EnumProductsPaymentPeriods;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\products\inputs\ProductsPaymentPeriodsFilterInput;
use app\modules\graphql\schema\types\products\ProductPaymentPeriodType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Throwable;

/**
 * Class ProductsPaymentPeriodsListField
 * @package app\modules\graphql\schema\types\products\fields
 */
class ProductsPaymentPeriodsListField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'productPaymentPeriodsList',
			'type' => Type::listOf(ProductPaymentPeriodType::type()),
			'description' => 'Список периодов списания, у продуктов',
			'args' => [
				'filters' => [
					'type' => new ProductsPaymentPeriodsFilterInput(),
				],
			]
		]);
	}

	/**
	 * @inheritdoc
	 * @throws Throwable
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): ?array
	{
		return static::enumResolve(EnumProductsPaymentPeriods::mapData(), static::filterValue($args, 'id'));
	}
}