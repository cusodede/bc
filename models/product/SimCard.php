<?php
declare(strict_types = 1);

namespace app\models\product;

use app\models\product\active_record\SimCardAR;

/**
 * Class SimCard
 * Описание симки и всех её жизненных процессов.
 * Симка заказывается
 */
class SimCard extends SimCardAR implements ProductInterface {

	public function doSell():?bool {
		// TODO: Implement doSell() method.
	}
}