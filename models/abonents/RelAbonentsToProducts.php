<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\abonents\active_record\RelAbonentsToProducts as ActiveRecordRelAbonentsToProducts;
use app\models\billing_journal\BillingJournal;
use app\models\products\Products;
use app\models\products\ProductsJournal;
use yii\db\ActiveQuery;

/**
 * Class RelAbonentsToProducts
 * @package app\models\abonents
 *
 * @property ProductsJournal[] $relatedProductsJournal
 * @property ProductsJournal $relatedLastProductsJournal
 * @property BillingJournal[] $relatedBillingJournal
 * @property Abonents $relatedAbonent
 * @property Products $relatedProduct
 */
class RelAbonentsToProducts extends ActiveRecordRelAbonentsToProducts
{
	/**
	 * {@inheritdoc}
	 */
	public function getRelatedProductsJournal(): ActiveQuery
	{
		return $this->hasMany(ProductsJournal::class, ['rel_abonents_to_products_id' => 'id']);
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

	public function setRelatedLastProductsJournal(ProductsJournal $status): void
	{
		$this->link('relatedLastProductsJournal', $status);
	}

	/**
	 * Получение актуального статуса по продукту из журнала.
	 * @return ActiveQuery
	 */
	public function getRelatedLastProductsJournal(): ActiveQuery
	{
		return $this->hasOne(ProductsJournal::class, ['rel_abonents_to_products_id' => 'id'])->orderBy(['created_at' => SORT_DESC]);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedBillingJournal(): ActiveQuery
	{
		return $this->hasMany(BillingJournal::class, ['rel_abonents_to_products_id' => 'id']);
	}
}