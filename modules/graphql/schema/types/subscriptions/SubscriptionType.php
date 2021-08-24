<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\subscriptions;

use app\models\products\Products;
use app\models\subscriptions\Subscriptions;
use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\types\products\ProductType;
use GraphQL\Type\Definition\Type;

/**
 * Class SubscriptionType
 * @package app\modules\graphql\schema\types\subscription
 */
class SubscriptionType extends BaseObjectType
{
	/**
	 * {@inheritdoc}
	 */
	protected function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор подписки',
				],
				'product_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор продукта',
				],
				'trial_count' => [
					'type' => Type::int(),
					'description' => 'Количество триального периода',
				],
				'product' => [
					'type' => ProductType::type(),
					'description' => 'Продукт',
					'resolve' => fn(Subscriptions $subscription): ?Products => $subscription->product,
				],
			],
		]);
	}
}