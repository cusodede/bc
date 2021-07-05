<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\extended;

use app\models\partners\Partners;
use app\models\products\EnumProductsPaymentPeriods;
use app\models\products\EnumProductsTypes;
use app\models\products\Products;
use app\models\products\ProductsSearch;
use app\models\subscriptions\EnumSubscriptionTrialUnits;
use app\modules\graphql\schema\common\Types;
use app\modules\graphql\schema\types\TypeTrait;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Class ProductType
 * @package app\modules\graphql\schema\types
 */
final class ProductType extends ObjectType
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
					'description' => 'Идентификатор продукта',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование продукта',
				],
				'price' => [
					'type' => Type::float(),
					'description' => 'Цена',
				],
				'description' => [
					'type' => Type::string(),
					'description' => 'Описание',
				],
				'start_date' => [
					'type' => Type::string(),
					'description' => 'Начало действия',
				],
				'end_date' => [
					'type' => Type::string(),
					'description' => 'Конец действия',
				],
				'payment_period' => [
					'type' => Types::productPaymentPeriodType(),
					'description' => 'Периодичность списания',
					'resolve' => fn(Products $product): ?array
						=> self::getOneFromEnum(EnumProductsPaymentPeriods::mapData(), ['id' => $product->payment_period]),
				],
				'type_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор типа',
				],
				'type' => [
					'type' => Types::productTypesType(),
					'description' => 'Тип продукта',
					'resolve' => fn(Products $product): ?array
						=> self::getOneFromEnum(EnumProductsTypes::mapData(), ['id' => $product->type_id]),
				],
				'partner_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор партнёра',
				],
				'partner' => [
					'type' => Types::partner(),
					'description' => 'Партнёр',
					'resolve' => fn(Products $product): ?Partners => $product->relatedPartner,
				],
				'trial_count' => [
					'type' => Type::int(),
					'description' => 'Триальный период',
					'resolve' => fn(Products $product): int => $product->relatedInstance->trial_count ?? 0,
				],
				'trial_unit' => [
					'type' => Types::subscriptionTrialUnitsType(),
					'description' => 'Единица измерения триального периода',
					'resolve' => fn(Products $product): ?array
						=> self::getOneFromEnum(EnumSubscriptionTrialUnits::mapData(), ['id' => $product->relatedInstance->units ?? 0]),
				],
				'created_at' => [
					'type' => Type::string(),
					'description' => 'Дата создания',
				],
			],
		]);
	}

	/**
	 * @return array
	 */
	public static function getListOfType(): array
	{
		return [
			'type' => Type::listOf(Types::product()),
			'args' => [
				'sort' => [
					'type' => Type::string(),
					'description' => 'Сортировка: name, -name, created_at, -created_at',
				],
				'partner_id' => [
					'type' => Type::int(),
					'description' => 'Фильтр id партнёра',
				],
				'category_id' => [
					'type' => Type::int(),
					'description' => 'Фильтр id категории партнёра',
				],
				'trial' => [
					'type' => Type::boolean(),
					'description' => 'Фильтр, триальный период (1|0)',
				],
			],
			'resolve' => function(Products $product = null, array $args = []): array {
				$productSearch = new ProductsSearch();
				ArrayHelper::setValue($args, 'pagination', false);
				return $productSearch
					->search(self::transformToSearchModelParams($productSearch, $args))
					->getModels();
			}
		];
	}

	/**
	 * @return array
	 */
	public static function getOneOfType(): array
	{
		return [
			'type' => Types::product(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'resolve' => fn(Products $product = null, array $args = []): ?Products
				=> Products::find()->where($args)->active()->one(),
		];
	}
}