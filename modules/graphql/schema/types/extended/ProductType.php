<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\extended;

use app\models\partners\Partners;
use app\models\products\EnumProductsPaymentPeriods;
use app\models\products\Products;
use app\models\subscriptions\EnumSubscriptionTrialUnits;
use app\modules\graphql\schema\types\Types;
use app\modules\graphql\schema\types\TypeTrait;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class ProductType
 * @package app\modules\graphql\schema\types
 */
class ProductType extends ObjectType
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
						=> static::getOneFromEnum(EnumProductsPaymentPeriods::mapData(), ['id' => $product->payment_period]),
				],
				'type_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор типа',
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
						=> static::getOneFromEnum(EnumSubscriptionTrialUnits::mapData(), ['id' => $product->relatedInstance->units ?? 0]
						),
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
			'resolve' => fn(Products $product = null, array $args = []): ?array
				=> Products::find()->where($args)->active()->all(),
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