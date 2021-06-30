<?php
declare(strict_types = 1);

namespace app\useCases;

use app\models\abonents\RelAbonentsToProducts;
use app\models\products\EnumProductsStatuses;
use app\models\products\ProductStatuses;

/**
 * Class SwitchOffProductCase
 * @package app\useCases
 */
class SwitchOffProductCase
{
	/**
	 * @param int $abonentId
	 * @param int $productId
	 */
	public function execute(int $abonentId, int $productId): void
	{
		$relation = RelAbonentsToProducts::Upsert(['abonent_id' => $abonentId, 'product_id' => $productId]);

		$config = ['status_id' => EnumProductsStatuses::STATUS_DISABLED, 'expire_date' => $relation->relatedLastProductStatus->expire_date];

		$relation->relatedLastProductStatus = new ProductStatuses($config);
	}
}