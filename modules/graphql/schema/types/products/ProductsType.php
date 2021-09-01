<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\types\products\fields\ProductFilterField;
use app\modules\graphql\schema\types\products\fields\ProductProfileField;
use app\modules\graphql\schema\types\products\fields\ProductsListField;
use app\modules\graphql\schema\types\products\fields\ProductSortField;
use app\modules\graphql\schema\types\products\fields\ProductsPaymentPeriodsListField;
use app\modules\graphql\schema\types\products\fields\ProductsTypesListField;

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
				'partnerProfile' 			=> ProductProfileField::field(),
				'productPaymentPeriodsList' => ProductsPaymentPeriodsListField::field(),
				'productTypesList' 			=> ProductsTypesListField::field(),
				'productFilter' 			=> new ProductFilterField(),
				'productSort' 				=> new ProductSortField(),
			]
		]);
	}
}