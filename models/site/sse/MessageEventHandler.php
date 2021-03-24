<?php
declare(strict_types = 1);

namespace app\models\core\sse;

use odannyc\Yii2SSE\SSEBase;

/**
 * Class MessageEventHandler
 */
class MessageEventHandler extends SSEBase {

	public static $i = 0;

	/**
	 * @inheritDoc
	 */
	public function check() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function update() {
		return "Update ".self::$i++;
	}
}