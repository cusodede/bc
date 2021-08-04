<?php
declare(strict_types = 1);

namespace app\models\billing_journal;

use app\models\abonents\Abonents;
use app\models\abonents\RelAbonentsToProducts;
use app\models\billing_journal\active_record\BillingJournal as ActiveRecordBillingJournal;
use app\models\products\Products;
use Exception;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class BillingJournal
 * @package app\models\billing_journal
 *
 * @property-read RelAbonentsToProducts $relatedAbonentsToProducts
 * @property-read Abonents $relatedAbonent
 * @property-read Products $relatedProduct
 * @property-read string $statusDesc
 */
class BillingJournal extends ActiveRecordBillingJournal
{
	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonentsToProducts(): ActiveQuery
	{
		return $this->hasOne(RelAbonentsToProducts::class, ['id' => 'rel_abonents_to_products_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonent(): ActiveQuery
	{
		return $this->hasOne(Abonents::class, ['id' => 'abonent_id'])->via('relatedAbonentsToProducts');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProduct(): ActiveQuery
	{
		return $this->hasOne(Products::class, ['id' => 'product_id'])->via('relatedAbonentsToProducts');
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getStatusDesc(): string
	{
		return EnumBillingJournalStatuses::getScalar($this->status_id);
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function calculateNewPaymentDate(): string
	{
		$expireDate = ArrayHelper::getValue($this->relatedAbonentsToProducts, 'relatedLastProductsJournal.expire_date');

		return date_create(($this->created_at < $expireDate) ? $expireDate : $this->created_at)
			->modify($this->relatedProduct->paymentDateModifier)
			->format('Y-m-d H:i:s');
	}
}