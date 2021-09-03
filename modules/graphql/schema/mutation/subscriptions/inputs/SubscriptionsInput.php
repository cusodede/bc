<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\subscriptions\inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class SubscriptionsInput
 * @package app\modules\graphql\schema\mutation\subscriptions\inputs
 */
class SubscriptionsInput extends InputObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct(string $rootName)
	{
		parent::__construct([
			'name' => $rootName . 'SubscriptionData',
			'fields' => [
				'trial_count' => [
					'type' => Type::int(),
					'description' => 'Количество триального периода',
				],
				'units' => [
					'type' => Type::int(),
					'description' => 'Единица измерения триального периода',
				],
			]
		]);
	}
}