<?php
declare(strict_types = 1);

namespace app\modules\api\resources\formatters;

use app\models\ticket\active_record\TicketJournal;
use app\models\ticket\TicketProductSubscription;
use yii\helpers\ArrayHelper;

/**
 * Class TicketProductSubscriptionFormatter
 * @package app\modules\api\resources\formatters
 */
class TicketProductSubscriptionFormatter
{
	/**
	 * @param TicketProductSubscription $ticket
	 * @return array
	 */
	public function format(TicketProductSubscription $ticket): array
	{
		return ArrayHelper::toArray($ticket, [
			TicketProductSubscription::class => [
				'action',
				'status' => static function (TicketProductSubscription $ticket) {
					if ($ticket->isCompleted) {
						return TicketJournal::STATUS_OK === $ticket->relatedLastTicketJournal->status
							? ['code' => 'success']
							: ['code' => 'error', 'desc' => ''/** TODO add description*/ ];
					}

					return ['code' => 'in_progress'];
				}
			]
		]);
	}
}