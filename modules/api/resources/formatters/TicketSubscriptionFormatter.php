<?php
declare(strict_types = 1);

namespace app\modules\api\resources\formatters;

use app\models\ticket\TicketSubscription;
use yii\helpers\ArrayHelper;

/**
 * Class TicketSubscriptionFormatter
 * @package app\modules\api\resources\formatters
 */
class TicketSubscriptionFormatter
{
	/**
	 * @param TicketSubscription $ticket
	 * @return array
	 */
	public function format(TicketSubscription $ticket): array
	{
		return ArrayHelper::toArray($ticket, [
			TicketSubscription::class => [
				'action',
				'status' => static function(TicketSubscription $ticket) {
					if ($ticket->isCompleted) {
						return $ticket->getIsStatusOk()
							? ['code' => 'success']
							: ['code' => 'error', 'desc' => $ticket->extractErrorDescriptionFromJournal()];
					}

					return ['code' => 'in_progress'];
				}
			]
		]);
	}
}