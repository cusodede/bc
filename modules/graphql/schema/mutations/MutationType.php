<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations;

use app\modules\graphql\schema\mutations\extended\PartnerMutationType;
use app\modules\graphql\schema\mutations\extended\ProductMutationType;
use app\modules\graphql\schema\mutations\extended\SubscriptionMutationType;
use GraphQL\Type\Definition\ObjectType;

/**
 * Class MutationType
 * @package app\modules\graphql\schema\mutations
 */
class MutationType extends ObjectType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'partner' => PartnerMutationType::mutationType(),
				'product' => ProductMutationType::mutationType(),
				'subscription' => SubscriptionMutationType::mutationType(),
			]
		]);
	}
}
