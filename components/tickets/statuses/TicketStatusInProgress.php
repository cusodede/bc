<?php
declare(strict_types = 1);

namespace app\components\tickets\statuses;

use app\helpers\DateHelper;
use DateTimeImmutable;

/**
 * Class TicketStatusInProgress
 * @package app\components\tickets\statuses
 */
class TicketStatusInProgress implements TicketStatusInterface
{
	private DateTimeImmutable $_reservedAt;

	/**
	 * TicketStatusInProgress constructor.
	 * @param DateTimeImmutable $reservedAt
	 */
	public function __construct(DateTimeImmutable $reservedAt)
	{
		$this->_reservedAt = $reservedAt;
	}

	/**
	 * {@inheritdoc}
	 */
	public function toArray(): array
	{
		return [
			'status'      => 'in_progress',
			'reserved_at' => DateHelper::toIso8601(DateHelper::from_unix_timestamp($this->_reservedAt->getTimestamp()))
		];
	}
}