<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products\inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class ProductsTypesFilterInput
 * @package app\modules\graphql\schema\types\products\inputs
 */
class ProductsTypesFilterInput extends InputObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор типа',
				],
			]
		]);
	}
}