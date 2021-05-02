<?php
declare(strict_types = 1);

namespace app\modules\notifications\models\handlers;


/**
 * Interface NotificationInterface
 */
interface NotificationInterface {

	public function process():bool;

}