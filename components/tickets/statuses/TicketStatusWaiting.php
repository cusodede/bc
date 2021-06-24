<?php
declare(strict_types = 1);

namespace app\components\tickets\statuses;

/**
 * Class TicketStatusWaiting
 * @package app\components\tickets\statuses
 */
class TicketStatusWaiting implements TicketStatusInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function toArray(): array
	{
		return [
			'status' => 'waiting'
		];
	}
}