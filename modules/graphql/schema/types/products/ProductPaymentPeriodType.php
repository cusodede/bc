<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products;

use app\modules\graphql\components\BaseObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class ProductPaymentPeriodType
 * @package app\modules\graphql\schema\types\products
 */
class ProductPaymentPeriodType extends BaseObjectType
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
					'description' => 'Идентификатор списания',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование списания',
				],
			],
		]);
	}
}