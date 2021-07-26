<?php
declare(strict_types = 1);

namespace app\components\tickets\statuses;

use app\components\helpers\DateHelper;
use DateTimeImmutable;

/**
 * Class TicketStatusDone
 * @package app\components\tickets\statuses
 */
class TicketStatusDone implements TicketStatusInterface
{
	private DateTimeImmutable $_doneAt;

	/**
	 * TicketStatusDone constructor.
	 * @param DateTimeImmutable $doneAt
	 */
	public function __construct(DateTimeImmutable $doneAt)
	{
		$this->_doneAt = $doneAt;
	}

	/**
	 * {@inheritdoc}
	 */
	public function toArray(): array
	{
		return [
			'status'  => 'done',
			'done_at' => DateHelper::toIso8601(DateHelper::from_unix_timestamp($this->_doneAt->getTimestamp()))
		];
	}
}