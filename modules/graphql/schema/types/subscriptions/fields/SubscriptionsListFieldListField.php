<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\subscriptions\fields;

use app\models\subscriptions\SubscriptionsSearch;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\subscriptions\SubscriptionType;
use app\modules\graphql\schema\types\subscriptions\inputs\SubscriptionsFilterInput;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Class SubscriptionsListFieldListField
 * @package app\modules\graphql\schema\types\subscriptions\fields
 */
class SubscriptionsListFieldListField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'subscriptionsList',
			'type' => Type::listOf(SubscriptionType::type()),
			'description' => 'Список подписок',
			'args' => [
				'filters' => [
					'type' => new SubscriptionsFilterInput(),
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
		$subscriptionSearch = new SubscriptionsSearch();
		$filters = ArrayHelper::getValue($args, 'filters', []);
		ArrayHelper::setValue($args, 'pagination', false);
		return $subscriptionSearch->search([$subscriptionSearch->formName() => ArrayHelper::merge($args, $filters)])->getModels();
	}
}