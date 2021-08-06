<?php
declare(strict_types = 1);

namespace app\models\ticket;

/**
 * Class TicketProductSubscriptionParams
 * @package app\models\ticket
 */
class TicketProductSubscriptionParams extends TicketParams
{
	public ?int $action = null;
	public ?int $productId = null;
	public ?int $abonentId = null;

	/**
	 * TicketProductSubscriptionParams constructor.
	 * @param array $config
	 */
	public function __construct($config = [])
	{
		parent::__construct($config);

		$this->type = Ticket::TYPE_SUBSCRIPTION;
	}
}