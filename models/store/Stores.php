<?php
declare(strict_types = 1);

namespace app\models\store;

use app\models\store\active_record\StoresAR;

/**
 * Class Stores
 * Точка продажи товаров (как организация): собственный офис, франчайзи, дилеры, etc.
 */
class Stores extends StoresAR {
	public const RUS_CLASS_NAME = 'Магазин';
}