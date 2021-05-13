<?php
declare(strict_types = 1);

namespace app\models\ref_products_types\active_record\references;

use app\models\ref_products_types\active_record\RefProductsTypes as ActiveRecordRefProductsTypes;
use pozitronik\references\models\Reference;

/**
 * Справочник типов продуктов
 * Class RefProductsTypes
 * @package app\models\ref_products_types\active_record\references
 */
class RefProductsTypes extends Reference
{
	public $menuCaption  = 'Типы продуктов';

	public static function tableName(): string
	{
		return ActiveRecordRefProductsTypes::tableName();
	}
}