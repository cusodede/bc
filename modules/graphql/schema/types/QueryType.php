<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use app\models\partners\Partners;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class QueryType
 * @package app\schema
 */
class QueryType extends ObjectType
{
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'partners' => [
					'type' => Type::listOf(Types::partner()),
					'args' => [
						'inn' => Type::string(),
					],
					'resolve' => fn(Partners $partner = null, array $args = []): ?array => Partners::find()->where($args)->all(),
				],
				'partner' => [
					'type' => Types::partner(),
					'args' => [
						'id' => Type::nonNull(Type::int()),
					],
					'resolve' => fn(Partners $partner = null, array $args = []): ?Partners => Partners::find()->where($args)->one(),
				],
			],
		]);
	}
}