<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\types\products\fields\ProductsListField;
use app\modules\graphql\schema\types\products\fields\ProductsPaymentPeriodsListField;

/**
 * Class ProductsType
 * @package app\modules\graphql\schema\types\products
 */
class ProductsType extends BaseObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct([
			'description' => 'Продукты',
			'fields' => [
				'productsList' 				=> ProductsListField::field(),
				'productPaymentPeriodsList' => ProductsPaymentPeriodsListField::field(),
			]
		]);
	}
}