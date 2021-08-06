<?php
declare(strict_types = 1);

namespace app\models\ticket;

use app\models\ticket\active_record\Ticket as TicketAR;
use Throwable;

/**
 * Class Ticket
 * @package app\models\ticket
 */
class Ticket extends TicketAR
{
	public const TYPE_SUBSCRIPTION = 1;

	/**
	 * @param TicketParams $params
	 * @return static
	 * @throws Throwable
	 */
	public static function createTicket(TicketParams $params): self
	{
		$ticket = new self(['id' => $params->id, 'type' => $params->type, 'created_by' => $params->createdBy]);
		$ticket->save();

		return $ticket;
	}
}