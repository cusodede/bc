<?php
declare(strict_types = 1);

namespace app\modules\notifications\models;

use Amp\Deferred;
use Amp\Loop;
use Amp\Promise;
use app\modules\notifications\models\active_record\Notifications;
use app\modules\notifications\models\handlers\NotificationInterface;
use function Amp\Promise\wait;

/**
 * Class Notification
 * @property NotificationInterface $handler
 * @property array $handlerParams
 */
class Notification extends Notifications {
	public $handler;
	public $handlerParams;

	public function send() {
		$promise = $this->process();
		$result = wait($promise);
	}

	/**
	 * @return Promise
	 */
	private function process() {
		$deferred = new Deferred();

		Loop::delay(10000, function() use ($deferred) {
			$handler = new $this->handler($this->handlerParams);
			$deferred->resolve($handler->process());
		});

		return $deferred->promise();
	}

}