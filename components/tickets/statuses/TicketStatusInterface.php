<?php
declare(strict_types = 1);

namespace app\components\tickets\statuses;

/**
 * Interface TicketStatusInterface
 * @package app\components\tickets\statuses
 */
interface TicketStatusInterface
{
	/**
	 * @return array информация по тикету.
	 */
	public function toArray(): array;
}