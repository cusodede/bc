<?php
declare(strict_types = 1);

namespace app\models\products;

use app\models\abonents\RelAbonentsToProducts;
use app\models\products\active_record\ProductStatuses as ProductsAliasAR;
use yii\db\ActiveQuery;

/**
 * Class ProductStatuses
 * @package app\models\products
 */
class ProductStatuses extends ProductsAliasAR
{
	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonentsToProducts(): ActiveQuery
	{
		return $this->hasOne(RelAbonentsToProducts::class, ['id' => 'rel_abonents_to_products_id']);
	}
}