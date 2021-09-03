<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products;

use app\modules\graphql\components\BaseObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class ProductTypesType
 * @package app\modules\graphql\schema\types\products
 */
class ProductTypesType extends BaseObjectType
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор типа продукта',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование типа продукта',
				],
			],
		]);
	}
}