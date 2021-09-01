<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products\fields;

use app\modules\graphql\schema\types\products\inputs\ProductsFilterInput;
use GraphQL\Type\Definition\EnumType;

/**
 * Class ProductFilterField
 * @package app\modules\graphql\schema\types\products\fields
 */
class ProductFilterField extends EnumType
{
	public function __construct()
	{
		parent::__construct([
			'name' => 'FilterProductField',
			'values' => array_keys((new ProductsFilterInput())->getFields()),
		]);
	}
}