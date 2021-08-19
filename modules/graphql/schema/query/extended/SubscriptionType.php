<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query\extended;

use app\models\subscriptions\EnumSubscriptionTrialUnits;
use app\models\subscriptions\Subscriptions;
use app\models\subscriptions\SubscriptionsSearch;
use app\modules\graphql\base\BaseQueryType;
use app\modules\graphql\data\EnumTypes;
use app\modules\graphql\data\QueryTypes;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Class SubscriptionType
 * @package app\modules\graphql\schema\query\extended
 */
final class SubscriptionType extends BaseQueryType
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
				'units' => [
					'type' => EnumTypes::subscriptionTrialUnitsType(),
					'description' => 'Единица измерения триального периода',
					'resolve' => fn(Subscriptions $subscription): ?array => self::getOneFromEnum(
						EnumSubscriptionTrialUnits::mapData(),
						['id' => $subscription->units]
					),
				],
				'product' => [
					'type' => QueryTypes::product(),
					'description' => 'Продукт',
					'resolve' => fn(Subscriptions $subscription) => $subscription->product,
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
			'type' => Type::listOf(QueryTypes::subscription()),
			'description' => 'Возвращаем список подписок',
			'resolve' => function(Subscriptions $subscription = null, array $args = []): array {
				$subscriptionSearch = new SubscriptionsSearch();
				ArrayHelper::setValue($args, 'pagination', false);
				return $subscriptionSearch->search([$subscriptionSearch->formName() => $args])->getModels();
			},
		];
	}

	/**
	 * @return array
	 */
	public static function getOneOfType(): array
	{
		return [
			'type' => QueryTypes::subscription(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'description' => 'Возвращает подписку по id',
			'resolve' => fn(Subscriptions $subscription = null, array $args = []): ?Subscriptions => Subscriptions::find()->where($args)->active()->one(),
		];
	}
}