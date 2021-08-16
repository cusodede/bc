<?php
declare(strict_types = 1);

namespace app\models\ticket;

use app\models\ticket\active_record\TicketSubscription as TicketSubscriptionActiveRecord;
use Throwable;
use Yii;

/**
 * Class TicketProductSubscription
 * @package app\models\ticket
 */
class TicketSubscription extends TicketSubscriptionActiveRecord
{
	public const ACTION_CONNECT_SUBSCRIPTION = 1;
	public const ACTION_DISABLE_SUBSCRIPTION = 2;

	public const STAGE_CODE_SERVICE_CHECK = 1001;
	public const STAGE_CODE_BILLING_DEBIT = 1002;
	public const STAGE_CODE_CONNECT_ON_PARTNER = 1003;

	/**
	 * @param TicketProductSubscriptionParams $params
	 * @return static
	 * @throws Throwable
	 */
	public static function createTicket(TicketProductSubscriptionParams $params): static
	{
		if (null !== $params->id) {
			$ticket = static::findOne($params->id);
			if (null !== $ticket) {
				return $ticket;
			}
		}

		return Yii::$app->db->transaction(static function() use ($params) {
			$ticket = new static(['id' => Ticket::createTicket($params)->id, 'action' => $params->action]);
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