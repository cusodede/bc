<?php
declare(strict_types = 1);

namespace app\models\products;

use app\models\abonents\RelAbonentsToProducts;
use app\models\products\active_record\ProductStatuses as ActiveRecordProductStatuses;
use yii\db\ActiveQuery;

/**
 * Class ProductStatuses
 * @package app\models\products
 *
 * @property-read string|null $statusName
 * @property-read bool $isEnabled
 * @property-read bool $isRenewed
 * @property-read bool $isDisabled
 * @property-read bool $isExpired
 *
 * @property RelAbonentsToProducts $relatedAbonentsToProducts
 */
class ProductStatuses extends ActiveRecordProductStatuses
{
	/**
	 * {@inheritdoc}
	 */
	public function getRelatedAbonentsToProducts(): ActiveQuery
	{
		return $this->hasOne(RelAbonentsToProducts::class, ['id' => 'rel_abonents_to_products_id']);
	}

	/**
	 * @return string|null именованное обозначение статуса.
	 */
	public function getStatusName(): ?string
	{
		return EnumProductsStatuses::getStatusName($this->status_id);
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
	public function getIsDisabled(): bool
	{
		return EnumProductsStatuses::STATUS_DISABLED === $this->status_id;
	}

	/**
	 * @return bool
	 */
	public function getIsExpired(): bool
	{
		return time() > strtotime($this->expire_date);
	}
}