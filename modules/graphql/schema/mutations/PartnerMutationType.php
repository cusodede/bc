<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations;

use app\models\partners\Partners;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class PartnerMutationType
 * @package app\modules\graphql\schema\mutations
 */
class PartnerMutationType extends ObjectType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'update' => [
					'type' => Type::boolean(),
					'description' => 'Обновление партнера',
					'args' => [
						'name' => Type::string(),
						'inn' => Type::string(),
					],
					'resolve' => function(Partners $partner, array $args = []): bool {
						$partner->setAttributes($args);
						return $partner->save();
					}
				],
			]
		]);
	}
}