<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\partners\inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class PartnersFilterInput
 * @package app\modules\graphql\schema\types\partners\inputs
 */
class PartnersFilterInput extends InputObjectType
{
	/**
	 * UsersFilterInput constructor.
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор партнёра',
				],
				'search' => [
					'type' => Type::string(),
					'description' => 'Поиск по ИНН и наименованию'
				],
				'inn' => [
					'type' => Type::string(),
					'description' => 'Поиск по ИНН'
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Поиск по наименованию'
				]
			]
		]);
	}
}