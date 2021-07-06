<?php
declare(strict_types = 1);

namespace app\components\subscription\use_cases;

use app\models\abonents\RelAbonentsToProducts;
use app\models\products\EnumProductsStatuses;
use app\models\products\ProductsJournal;

/**
 * Class ActivateProductCase
 * @package app\components\subscription\use_cases
 */
class ActivateProductCase
{
	/**
	 * @param int $abonentId
	 * @param int $productId
	 * @param string $expireDate
	 */
	public function execute(int $abonentId, int $productId, string $expireDate): void
	{
		$relation = RelAbonentsToProducts::Upsert(['abonent_id' => $abonentId, 'product_id' => $productId]);
		if (null === $relation->relatedLastProductsJournal) {
			$config = ['status_id' => EnumProductsStatuses::STATUS_ENABLED, 'expire_date' => $expireDate];
		} else {
			$config = ['status_id' => EnumProductsStatuses::STATUS_RENEWED, 'expire_date' => $expireDate];
		}

		$relation->relatedLastProductsJournal = new ProductsJournal($config);
	}
}