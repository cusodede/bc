<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\abonents\active_record\RelAbonentsToProducts as RelAbonentsToProductsAR;
use app\models\products\Products;
use app\models\products\ProductStatuses;
use yii\db\ActiveQuery;

/**
 * Class RelAbonentsToProducts
 * @package app\models\abonents
 *
 * @property ProductStatuses[] $relatedProductStatuses
 * @property ProductStatuses $relatedLastProductStatus
 * @property Abonents $relatedAbonent
 * @property Products $relatedProduct
 */
class RelAbonentsToProducts extends RelAbonentsToProductsAR
{
	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProductStatuses(): ActiveQuery
	{
		return $this->hasMany(ProductStatuses::class, ['rel_abonents_to_products_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedLastProductStatus(): ActiveQuery
	{
		//TODO: учесть алиас таблицы в orderBy
		return $this->hasOne(ProductStatuses::class, ['rel_abonents_to_products_id' => 'id'])->orderBy(['product_statuses.created_at' => SORT_DESC]);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonent(): ActiveQuery
	{
		return $this->hasOne(Abonents::class, ['id' => 'abonent_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProduct(): ActiveQuery
	{
		return $this->hasOne(Products::class, ['id' => 'product_id']);
	}
}