<?php
declare(strict_types = 1);

namespace app\models\site\sse;

use odannyc\Yii2SSE\SSEBase;
use Yii;

/**
 * Class MessageEventHandler
 */
class MessageEventHandler extends SSEBase {

	public ?array $messages = null;


	/**
	 * @inheritDoc
	 */
	public function check():bool {
		sleep(random_int(1, 2));
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function update():string {
		$messages = Yii::$app->cache->get('mvp');
		if (empty($this->messages) && is_array($messages) && in_array('stop', $messages, true)) {
			$this->messages = $messages;
		}

		if (is_array($this->messages) && count($this->messages) > 0) {
			$val = array_shift($this->messages);
			if ([] === $this->messages) {
				Yii::$app->cache->delete('mvp');
			}

			return $val;
		}
		return '';
	}
}