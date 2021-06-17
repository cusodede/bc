<?php
declare(strict_types = 1);

namespace app\models\products;

use app\models\products\active_record\Products as ActiveRecordProducts;
use app\models\subscriptions\Subscriptions;

/**
 * Логика продуктов, не относящиеся к ActiveRecord
 * Class Products
 * @package app\models\product
 */
class Products extends ActiveRecordProducts
{
	public function getRelatedInstance(): ?Subscriptions
	{
		//TODO подтягивание корректной модели в зависимости от типа продукта
		return Subscriptions::findOne(['product_id' => $this->id]);
	}
}