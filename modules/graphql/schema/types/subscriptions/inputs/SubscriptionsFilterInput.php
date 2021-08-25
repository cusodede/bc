<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\subscriptions\inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class SubscriptionsFilterInput
 * @package app\modules\graphql\schema\types\subscriptions\inputs
 */
class SubscriptionsFilterInput extends InputObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор подписки',
				],
			]
		]);
	}
}