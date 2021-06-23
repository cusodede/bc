<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\abonents\active_record\RelAbonentsToProducts as ActiveRecordRelAbonentsToProducts;
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
class RelAbonentsToProducts extends ActiveRecordRelAbonentsToProducts
{
	/**
	 * {@inheritdoc}
	 */
	public function getRelatedProductStatuses(): ActiveQuery
	{
		return $this->hasMany(ProductStatuses::class, ['rel_abonents_to_products_id' => 'id']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRelatedAbonent(): ActiveQuery
	{
		return $this->hasOne(Abonents::class, ['id' => 'abonent_id']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRelatedProduct(): ActiveQuery
	{
		return $this->hasOne(Products::class, ['id' => 'product_id']);
	}

	public function setRelatedLastProductStatus(ProductStatuses $status): void
	{
		$this->unlink('relatedLastProductStatus', $status);
	}

	/**
	 * Получение актуального статуса по продукту из журнала.
	 * @return ActiveQuery
	 */
	public function getRelatedLastProductStatus(): ActiveQuery
	{
		return $this->hasOne(ProductStatuses::class, ['rel_abonents_to_products_id' => 'id'])->orderBy(['created_at' => SORT_DESC]);
	}
}