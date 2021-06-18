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
}