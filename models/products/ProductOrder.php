<?php
declare(strict_types = 1);

namespace app\models\products;

use app\models\products\active_record\ProductOrderAR;

/**
 * Class ProductOrder
 * Описание заказа
 * @property ProductInterface[] $default Список товаров в заказе
 */
class ProductOrder extends ProductOrderAR {

}