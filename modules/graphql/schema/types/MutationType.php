<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use app\models\partners\Partners;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

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
				'partner' => [
					'type' => Types::partnerMutation(),
					'args' => [
						'id' => Type::nonNull(Type::int()),
					],
					'resolve' => fn(Partners $partner = null, array $args = []): ?Partners => Partners::find()->where($args)->one(),
				],
			]
		]);
	}
}