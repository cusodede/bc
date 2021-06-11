<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class PartnerType
 * @package app\modules\graphql\schema\types
 */
class PartnerType extends ObjectType
{
	/**
	 * PartnerType constructor.
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор партнера',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование юридического лица партнера',
				],
				'inn' => [
					'type' => Type::string(),
					'description' => 'ИНН партнера',
				],
			],
		]);
	}
}