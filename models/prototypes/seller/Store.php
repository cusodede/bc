<?php
declare(strict_types = 1);

namespace app\models\prototypes\seller;

use app\models\prototypes\seller\references\RefStoreType;

/**
 * Class Store
 * Точка продажи товаров (как организация): собственный офис, франчайзи, дилеры, etc.
 * @property RefStoreType $refStoreType Тип точки (справочник)
 * @property Seller[] $sellers Все продавцы точки
 */
class Store {



}