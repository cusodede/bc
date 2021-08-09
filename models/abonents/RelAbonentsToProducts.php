<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\abonents\active_record\RelAbonentsToProducts as ActiveRecordRelAbonentsToProducts;
use app\models\billing_journal\BillingJournal;
use app\models\products\EnumProductsStatuses;
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
		$status->validate(['id']);

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

	/**
	 * Запись в журнал информации о продлении подписки.
	 * @param string $expireDate
	 */
	public function enable(string $expireDate): void
	{
		$config = [
			'expire_date' => $expireDate,
			'status_id'   => (null === $this->relatedLastProductsJournal)
				? EnumProductsStatuses::STATUS_ENABLED
				: EnumProductsStatuses::STATUS_RENEWED
		];

		$this->relatedLastProductsJournal = new ProductsJournal($config);
	}

	/**
	 * Запись в журнал информации о деактивации подписки.
	 * Предусмотрено несколько вариантов блокировки подписки (недостаточно денег, запрос от клиента и т.д.).
	 * @param int $statusId
	 */
	public function disable(int $statusId = EnumProductsStatuses::STATUS_DISABLED): void
	{
		$config = ['status_id' => $statusId, 'expire_date' => $this->relatedLastProductsJournal->expire_date];

		$this->relatedLastProductsJournal = new ProductsJournal($config);
	}
}