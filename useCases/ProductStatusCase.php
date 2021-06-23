<?php
declare(strict_types = 1);

namespace app\useCases;

use app\models\abonents\RelAbonentsToProducts;
use app\models\products\EnumProductsStatuses;
use app\models\products\ProductStatuses;

/**
 * Class ProductStatusCase
 * @package app\useCases
 */
class ProductStatusCase
{
	/**
	 * @param int $abonentId
	 * @param int $productId
	 * @param int $statusId
	 */
	public function update(int $abonentId, int $productId, int $statusId): void
	{
		$relation = RelAbonentsToProducts::Upsert(['abonent_id' => $abonentId, 'product_id' => $productId]);
		if (null === $relation->relatedLastProductStatus) {
			$config = ['status_id' => EnumProductsStatuses::STATUS_ENABLED, 'expire_date' => ''];//TODO calculate new expire_date
		} elseif ($statusId === EnumProductsStatuses::STATUS_DISABLED) {
			$config = ['status_id' => $statusId, 'expire_date' => $relation->relatedLastProductStatus->expire_date];
		} else {
			$config = ['status_id' => $statusId, 'expire_date' => ''];//TODO calculate new expire_date
		}

		$relation->relatedLastProductStatus = new ProductStatuses($config);
	}
}