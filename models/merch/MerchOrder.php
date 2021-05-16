<?php
declare(strict_types = 1);

namespace app\models\merch;

use app\models\merch\active_record\MerchOrderAR;

/**
 * Class MerchOrder
 * Описание заказа
 * @property MerchInterface[] $default Список товаров в заказе
 */
class MerchOrder extends MerchOrderAR {

}