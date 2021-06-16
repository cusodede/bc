<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations;

use app\models\partners\Partners;
use app\modules\graphql\GraphqlHelper;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use app\modules\graphql\schema\types\Types;

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
					'type' => Types::validationErrorsUnionType(Types::partner()),
					'description' => 'Обновление партнера',
					'args' => [
						'name' => Type::string(),
						'inn' => Type::string(),
					],
					'resolve' => function(Partners $partner, array $args = []) {
						$partner->setAttributes($args);
						return $partner->save() ? $partner : GraphqlHelper::getErrors($partner->getErrors());
					}
				],
			]
		]);
	}
}