<?php
declare(strict_types = 1);

namespace app\models\ticket;

use app\models\products\ProductsJournal;
use app\models\ticket\active_record\TicketSubscription as TicketSubscriptionActiveRecord;
use Throwable;
use Yii;

/**
 * Class TicketSubscription
 * @package app\models\ticket
 */
class TicketSubscription extends TicketSubscriptionActiveRecord
{
	public const ACTION_CONNECT_SUBSCRIPTION = 1;
	public const ACTION_DISABLE_SUBSCRIPTION = 2;

	public const STAGE_CODE_ABONENT_VERIFICATION = 1001;
	public const STAGE_CODE_SERVICE_VERIFICATION = 1002;
	public const STAGE_CODE_BILLING_DEBIT = 1003;
	public const STAGE_CODE_CONNECT_ON_PARTNER = 1004;

	/**
	 * @param TicketSubscriptionParams $params
	 * @return static
	 * @throws Throwable
	 */
	public static function createTicket(TicketSubscriptionParams $params): static
	{
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
	 * @return ProductsJournal|null
	 * @noinspection PhpIncompatibleReturnTypeInspection see [[ActiveQuery::one()]].
	 */
	public function findLastProductJournal(): ?ProductsJournal
	{
		return ProductsJournal::find()
			->where(['rel_abonents_to_products_id' => $this->rel_abonents_to_products_id])
			->orderBy(['created_at' => SORT_DESC])
			->limit(1)
			->one();
	}

	/**
	 * @return bool
	 */
	public function isConnectMode(): bool
	{
		return self::ACTION_CONNECT_SUBSCRIPTION === $this->action;
	}
}