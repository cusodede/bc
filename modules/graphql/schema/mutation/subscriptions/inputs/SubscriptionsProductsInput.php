<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\subscriptions\inputs;

use app\modules\graphql\schema\definition\DateTimeType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class SubscriptionsProductsInput
 * @package app\modules\graphql\schema\mutation\subscriptions\inputs
 */
class SubscriptionsProductsInput extends InputObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct(string $rootName)
	{
		parent::__construct([
			'name' => $rootName . 'SubscriptionProductData',
			'fields' => [
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование продукта',
				],
				'partner_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор партнёра',
				],
				'price' => [
					'type' => Type::float(),
					'description' => 'Цена',
				],
				'payment_period' => [
					'type' => Type::int(),
					'description' => 'Периодичность списания',
				],
				'start_date' => [
					'type' => DateTimeType::type(),
					'description' => 'Начало действия Y-m-d H:i:s',
				],
				'end_date' => [
					'type' => DateTimeType::type(),
					'description' => 'Конец действия Y-m-d H:i:s',
				],
				'description' => [
					'type' => Type::string(),
					'description' => 'Краткое описание продукта',
				],
				'ext_description' => [
					'type' => Type::string(),
					'description' => 'Полное описание продукта',
				],
				'trial_count' => [
					'type' => Type::int(),
					'description' => 'Количество триального периода',
				],
				'units' => [
					'type' => Type::int(),
					'description' => 'Единица измерения триального периода',
				],
			],
		]);
	}
}