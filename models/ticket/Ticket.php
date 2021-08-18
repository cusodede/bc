<?php
declare(strict_types = 1);

namespace app\models\ticket;

use app\models\ticket\active_record\Ticket as TicketActiveRecord;
use Throwable;

/**
 * Class Ticket
 * @package app\models\ticket
 */
class Ticket extends TicketActiveRecord
{
	public const TYPE_SUBSCRIPTION = 1;

	public const STAGE_CODE_INIT = 1000;

	public const STATUS_OK = 1;
	public const STATUS_ERROR = 2;

	/**
	 * @param TicketParams $params
	 * @return static
	 * @throws Throwable
	 */
	public static function createTicket(TicketParams $params): static
	{
		$ticket = new static([
			'id'         => $params->id,
			'type'       => $params->type,
			'created_by' => $params->createdBy,
			'stage_id'   => self::STAGE_CODE_INIT
		]);
		$ticket->save();

		return $ticket;
	}
}