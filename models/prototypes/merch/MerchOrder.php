<?php
declare(strict_types = 1);

namespace app\models\prototypes\merch;

use app\models\prototypes\merch\active_record\MerchOrder as ActiveRecordMerchOrder;
use app\models\prototypes\merch\active_record\references\RefMerchOrderStatuses;

/**
 * Class MerchOrder
 * Описание заказа
 * @property RefMerchOrderStatuses $refOrderState Состояние заказа (справочник)
 * @property MerchInterface[] $default Список товаров в заказе
 */
class MerchOrder extends ActiveRecordMerchOrder {

}