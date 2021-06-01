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

	/**
	 * В этом методе будем проверять данные в других сервисах (Госуслуги, налоговая и т.д.)
	 * @param array $post POST with seller data
	 * @return bool
	 */
	public function checkData(array $post):bool {
		return true;
	}
}