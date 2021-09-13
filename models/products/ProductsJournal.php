<?php
declare(strict_types = 1);

namespace app\models\products;

use app\models\products\active_record\ProductsJournal as ActiveRecordProductsJournal;
use app\models\abonents\Abonents;
use app\models\abonents\RelAbonentsToProducts;
use Exception;
use yii\db\ActiveQuery;

/**
 * Class ProductsJournal
 * @package app\models\products
 *
 * @property RelAbonentsToProducts $relatedAbonentsToProducts
 * @property-read Products $relatedProduct
 * @property-read Abonents $relatedAbonent
 * @property-read string|null $statusDesc
 * @property-read bool $isEnabled
 * @property-read bool $isRenewed
 * @property-read bool $isActive
 * @property-read bool $isDisabled
 * @property-read bool $isExpired
 */
class ProductsJournal extends ActiveRecordProductsJournal
{
	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProduct(): ActiveQuery
	{
		return $this->hasOne(Products::class, ['id' => 'product_id'])->via('relatedAbonentsToProducts');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonent(): ActiveQuery
	{
		return $this->hasOne(Abonents::class, ['id' => 'abonent_id'])->via('relatedAbonentsToProducts');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRelatedAbonentsToProducts(): ActiveQuery
	{
		return $this->hasOne(RelAbonentsToProducts::class, ['id' => 'rel_abonents_to_products_id']);
	}

	/**
	 * @return string|null именованное обозначение статуса.
	 * @throws Exception
	 */
	public function getStatusDesc(): ?string
	{
		return EnumProductsStatuses::getScalar($this->status_id);
	}

	/**
	 * @return bool
	 */
	public function getIsEnabled(): bool
	{
		return EnumProductsStatuses::STATUS_ENABLED === $this->status_id;
	}

	/**
	 * @return bool
	 */
	public function getIsRenewed(): bool
	{
		return EnumProductsStatuses::STATUS_RENEWED === $this->status_id;
	}

	/**
	 * @return bool
	 */
	public function getIsActive(): bool
	{
		return $this->isEnabled || $this->isRenewed;
	}

	/**
	 * @return bool
	 */
	public function getIsDisabled(): bool
	{
		return EnumProductsStatuses::isInactive($this->status_id);
	}

	/**
	 * @return bool
	 */
	public function getIsExpired(): bool
	{
		return time() > strtotime($this->expire_date);
	}

	/**
	 * @return string|null
	 */
	public function findFirstConnectionDate(): ?string
	{
		/** @var static $record */
		$record = static::find()
			->where(['rel_abonents_to_products_id' => $this->rel_abonents_to_products_id])
			->orderBy(['created_at' => SORT_ASC])
			->limit(1)
			->one();

		return $record->created_at;
	}
}