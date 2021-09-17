<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\abonents;

use app\models\products\ProductsSearch;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\abonents\inputs\AbonentProductsFilterInput;
use app\modules\graphql\schema\types\products\ProductType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Список продуктов у абонента.
 */
class AbonentProductsListField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'abonentProductsList',
			'type' => Type::listOf(ProductType::type()),
			'description' => 'Список продуктов абонента',
			'args' => [
				'filters' => [
					'type' => new AbonentProductsFilterInput(),
				],
			]
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