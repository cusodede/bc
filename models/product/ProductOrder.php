<?php
declare(strict_types = 1);

namespace app\models\product;

use app\models\product\active_record\ProductOrderAR;

/**
 * Class ProductOrder
 * Описание заказа
 * @property ProductInterface[] $default Список товаров в заказе
 */
class ProductOrder extends ProductOrderAR {
	public function isSimcard():bool {
		return true;
	}
}