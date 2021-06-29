<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use app\modules\graphql\schema\mutations\PartnerMutationType;
use GraphQL\Type\Definition\ObjectType;

/**
 * Class MutationType
 * @package app\modules\graphql\schema\types
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
			]
		]);
	}
}