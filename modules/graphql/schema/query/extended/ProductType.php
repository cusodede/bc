<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query\extended;

use app\models\partners\Partners;
use app\models\products\EnumProductsPaymentPeriods;
use app\models\products\EnumProductsTypes;
use app\models\products\Products;
use app\models\products\ProductsSearch;
use app\models\subscriptions\EnumSubscriptionTrialUnits;
use app\modules\graphql\base\BaseQueryType;
use app\modules\graphql\data\EnumTypes;
use app\modules\graphql\data\QueryTypes;
use app\modules\graphql\definition\DateTimeType;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;
use DateTimeImmutable;

/**
 * Class ProductType
 * @package app\modules\graphql\schema\query\extended
 */
final class ProductType extends BaseQueryType
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
					'description' => 'Краткое описание',
				],
				'ext_description' => [
					'type' => Type::string(),
					'description' => 'Полное описание',
				],
				'start_date' => [
					'type' => DateTimeType::dateTime(),
					'description' => 'Начало действия Y-m-d H:i:s',
					'resolve' => fn(Products $products): ?DateTimeImmutable => DateTimeType::parseString($products->start_date)
				],
				'end_date' => [
					'type' => DateTimeType::dateTime(),
					'description' => 'Конец действия Y-m-d H:i:s',
					'resolve' => fn(Products $products): ?DateTimeImmutable => DateTimeType::parseString($products->end_date)
				],
				'payment_period' => [
					'type' => EnumTypes::productPaymentPeriodType(),
					'description' => 'Периодичность списания',
					'resolve' => fn(Products $product): ?array => self::getOneFromEnum(
						EnumProductsPaymentPeriods::mapData(),
						['id' => $product->payment_period]
					),
				],
				'type_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор типа',
				],
				'type' => [
					'type' => EnumTypes::productTypesType(),
					'description' => 'Тип продукта',
					'resolve' => fn(Products $product): ?array => self::getOneFromEnum(
						EnumProductsTypes::mapData(),
						['id' => $product->type_id]
					),
				],
				'partner_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор партнёра',
				],
				'partner' => [
					'type' => QueryTypes::partner(),
					'description' => 'Партнёр',
					'resolve' => fn(Products $product): ?Partners => $product->relatedPartner,
				],
				'trial_count' => [
					'type' => Type::int(),
					'description' => 'Триальный период',
					'resolve' => fn(Products $product): int => $product->relatedInstance->trial_count ?? 0,
				],
				'trial_unit' => [
					'type' => EnumTypes::subscriptionTrialUnitsType(),
					'description' => 'Единица измерения триального периода',
					'resolve' => fn(Products $product): ?array => self::getOneFromEnum(
						EnumSubscriptionTrialUnits::mapData(),
						['id' => $product->relatedInstance->units ?? 0]
					),
				],
				'created_at' => [
					'type' => DateTimeType::dateTime(),
					'description' => 'Дата создания Y-m-d H:i:s',
					'resolve' => fn(Products $products): ?DateTimeImmutable => DateTimeType::parseString($products->created_at),
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
			'type' => Type::listOf(QueryTypes::product()),
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
					'description' => 'Фильтр, триальный период (true|false)',
				],
				'active' => [
					'type' => Type::boolean(),
					'description' => 'Фильтр, по активности (true|false)',
				],
			],
			'description' => 'Возвращает список продуктов',
			'resolve' => function(Products $product = null, array $args = []): array {
				$productSearch = new ProductsSearch();
				ArrayHelper::setValue($args, 'pagination', false);
				return $productSearch
					->search([$productSearch->formName() => $args])
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
			'type' => QueryTypes::product(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'description' => 'Возвращает продукт по id',
			'resolve' => fn(Products $product = null, array $args = []): ?Products => Products::find()->where($args)->active()->one(),
		];
	}
}