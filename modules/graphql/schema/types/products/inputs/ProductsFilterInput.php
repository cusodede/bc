<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products\inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class ProductsFilterInput
 * @package app\modules\graphql\schema\types\products\inputs
 */
class ProductsFilterInput extends InputObjectType
{
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор продукта',
				],
				'partner_id' => [
					'type' => Type::int(),
					'description' => 'Фильтр id партнёра',
				],
				'category_id' => [
					'type' => Type::int(),
					'description' => 'Фильтр id категории партнёра',
				],
				'trial' => [
					'type' => Type::boolean(),
					'description' => 'Фильтр, триальный период (true|false)',
				],
				'active' => [
					'type' => Type::boolean(),
					'description' => 'Фильтр, по активности (true|false)',
				],
			]
		]);
	}
}