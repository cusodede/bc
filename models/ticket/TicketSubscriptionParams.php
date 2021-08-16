<?php
declare(strict_types = 1);

namespace app\models\ticket;

/**
 * Class TicketSubscriptionParams
 * @package app\models\ticket
 */
class TicketSubscriptionParams extends TicketParams
{
	public ?int $action = null;
	public ?int $productId = null;
	public ?int $abonentId = null;

	/**
	 * TicketSubscriptionParams constructor.
	 * @param array $config
	 */
	public function __construct($config = [])
	{
		parent::__construct($config);

		$this->type = Ticket::TYPE_SUBSCRIPTION;
	}
}