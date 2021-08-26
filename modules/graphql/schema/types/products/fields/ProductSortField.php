<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products\fields;

use GraphQL\Type\Definition\EnumType;

/**
 * Class ProductSortField
 * @package app\modules\graphql\schema\types\products\fields
 */
class ProductSortField extends EnumType
{
	public function __construct()
	{
		parent::__construct([
			'name' => 'SortProductField',
			'values' => [
				'CreatedAtAsc' 		=> ['value' => 'created_at'],
				'CreatedAtDesc' 	=> ['value' => '-created_at'],
				'NameAsc' 			=> ['value' => 'name'],
				'NameDesc' 			=> ['value' => '-name'],
			],
		]);
	}
}