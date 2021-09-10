<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\users\fields;

use GraphQL\Type\Definition\EnumType;

/**
 * Enum для сортировки пользователей.
 */
class UserSortField extends EnumType
{
	public function __construct(string $name = '')
	{
		parent::__construct([
			'name' => $name . 'SortUserField',
			'values' => [
				'CreatedAtAsc' 		=> ['value' => 'create_date'],
				'CreatedAtDesc' 	=> ['value' => '-create_date'],
				'SurnameAsc' 		=> ['value' => 'surname'],
				'SurnameDesc' 		=> ['value' => '-surname'],
			],
		]);
	}
}