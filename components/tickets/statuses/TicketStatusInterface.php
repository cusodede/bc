<?php
declare(strict_types = 1);

namespace app\components\tickets\statuses;

/**
 * Interface TicketStatusInterface
 * @package app\components\tickets\statuses
 */
interface TicketStatusInterface
{
	public function toArray(): array;
}