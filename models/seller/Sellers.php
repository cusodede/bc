<?php
declare(strict_types = 1);

namespace app\models\seller;

use app\models\seller\active_record\SellersAR;
use pozitronik\filestorage\traits\FileStorageTrait;

/**
 * Class Sellers
 * Конкретный продавец
 */
class Sellers extends SellersAR {
	use FileStorageTrait;
}