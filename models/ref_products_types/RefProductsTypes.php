<?php
declare(strict_types = 1);

namespace app\models\ref_products_types;

use app\models\ref_products_types\active_record\RefProductsTypes as ActiveRecordRefProductsTypes;

/**
 * Class RefProductsTypes
 * @package app\models\ref_products_types
 */
class RefProductsTypes extends ActiveRecordRefProductsTypes
{
	public const ID_SUBSCRIPTION = 1;
	public const ID_BUNDLE = 2;
}