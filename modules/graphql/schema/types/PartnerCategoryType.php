<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Категории партнеров
 * Class PartnerCategoryType
 * @package app\modules\graphql\schema\types
 */
class PartnerCategoryType extends ObjectType
{
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор категории',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование категории',
				],
			],
		]);
	}
}