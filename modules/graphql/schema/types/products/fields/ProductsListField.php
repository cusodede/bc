<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products\fields;

use app\models\products\ProductsSearch;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\products\inputs\ProductsFilterInput;
use app\modules\graphql\schema\types\products\ProductType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Class ProductsListField
 * @package app\modules\graphql\schema\types\products\fields
 */
class ProductsListField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'productsList',
			'type' => Type::listOf(ProductType::type()),
			'description' => 'Список продуктов',
			'args' => [
				'filters' => [
					'type' => new ProductsFilterInput(),
				],
				'limit' => Type::nonNull(Type::int()),
				'offset' => Type::nonNull(Type::int())
			],
			'resolve' => fn(mixed $root, array $args, mixed $context, ResolveInfo $resolveInfo): array => static::resolve(
				$root, $args, $context, $resolveInfo
			)
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): array
	{
		$productSearch = new ProductsSearch();
		$filters = ArrayHelper::getValue($args, 'filters', []);
		ArrayHelper::setValue($args, 'pagination', false);
		return $productSearch->search([$productSearch->formName() => ArrayHelper::merge($args, $filters)])->getModels();
	}
}