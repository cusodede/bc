<?php
declare(strict_types = 1);

namespace app\models\dealers;

use app\models\dealers\active_record\DealersAR;

/**
 * Class Dealers
 */
class Dealers extends DealersAR {

	/**
	 * Заглушка, чтобы инспектор не ругался на пустой стаб
	 */
	public function dummy_todo_function():void {

	}

}