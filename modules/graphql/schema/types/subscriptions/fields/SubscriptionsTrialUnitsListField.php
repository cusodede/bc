<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\subscriptions\fields;

use app\models\subscriptions\EnumSubscriptionTrialUnits;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\subscriptions\inputs\SubscriptionsTrialUnitsFilterInput;
use app\modules\graphql\schema\types\subscriptions\SubscriptionTrialUnitsType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Throwable;

/**
 * Class SubscriptionsTrialUnitsListField
 * @package app\modules\graphql\schema\types\subscriptions\fields
 */
class SubscriptionsTrialUnitsListField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'subscriptionTrialUnitsList',
			'type' => Type::listOf(SubscriptionTrialUnitsType::type()),
			'description' => 'Список единиц измерения триального периода',
			'args' => [
				'filters' => [
					'type' => new SubscriptionsTrialUnitsFilterInput(),
				],
			],
		]);
	}

	/**
	 * @inheritdoc
	 * @throws Throwable
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): ?array
	{
		return static::enumResolve(EnumSubscriptionTrialUnits::mapData(), static::filterValue($args, 'id'));
	}
}