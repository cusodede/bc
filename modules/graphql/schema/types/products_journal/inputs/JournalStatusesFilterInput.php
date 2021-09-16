<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products_journal\inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Фильтр по статусам журнала продуктов.
 */
class JournalStatusesFilterInput extends InputObjectType
{
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор статуса',
				],
			]
		]);
	}
}