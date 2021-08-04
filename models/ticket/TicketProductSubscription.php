<?php
declare(strict_types = 1);

namespace app\models\ticket;

use app\models\ticket\active_record\TicketProductSubscription as TicketProductSubscriptionAR;
use Throwable;
use Yii;

/**
 * Class TicketProductSubscription
 * @package app\models\ticket
 */
class TicketProductSubscription extends TicketProductSubscriptionAR
{
	public const ACTION_CONNECT_SUBSCRIPTION = 1;
	public const ACTION_DISABLE_SUBSCRIPTION = 2;

	/**
	 * @param TicketProductSubscriptionParams $params
	 * @return static
	 * @throws Throwable
	 */
	public static function createTicket(TicketProductSubscriptionParams $params): self
	{
		if (null !== $params->id && null !== $ticket = self::findOne($params->id)) {
			return $ticket;
		}

		return Yii::$app->db->transaction(static function() use ($params) {
			$ticket = new self(['id' => Ticket::createTicket($params)->id, 'action' => $params->action]);
			$ticket->save();

			$ticket->relatedAbonentsToProducts = [
				'product_id' => $params->productId,
				'abonent_id' => $params->abonentId
			];

			return $ticket;
		});
	}

	/**
	 * @return bool
	 */
	public function getIsConnectNeeded(): bool
	{
		return self::ACTION_CONNECT_SUBSCRIPTION === $this->action;
	}
}