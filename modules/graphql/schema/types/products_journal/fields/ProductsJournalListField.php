<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products_journal\fields;

use app\models\products\ProductsJournalSearch;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\products_journal\inputs\ProductsJournalFilterInput;
use app\modules\graphql\schema\types\products_journal\ProductJournalType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Журнал подписок абонента.
 */
class ProductsJournalListField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'productsJournalList',
			'type' => Type::listOf(ProductJournalType::type()),
			'description' => 'История операций подписок абонента',
			'args' => [
				'filters' => [
					'type' => new ProductsJournalFilterInput(),
				],
			],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): array
	{
		$productsJournalSearch = new ProductsJournalSearch();
		$filters = ArrayHelper::getValue($args, 'filters', []);
		ArrayHelper::setValue($args, 'pagination', false);
		return $productsJournalSearch->search([$productsJournalSearch->formName() => ArrayHelper::merge($args, $filters)])->getModels();
	}
}