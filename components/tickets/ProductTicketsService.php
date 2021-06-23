<?php
declare(strict_types = 1);

namespace app\components\tickets;

use app\components\tickets\statuses\TicketStatusDone;
use app\components\tickets\statuses\TicketStatusInProgress;
use app\components\tickets\statuses\TicketStatusInterface;
use app\components\tickets\statuses\TicketStatusWaiting;
use app\helpers\DateHelper;
use app\modules\api\components\queue\job\EnableProductJob;
use app\modules\api\components\queue\job\DisableProductJob;
use Yii;
use yii\queue\Queue;

/**
 * Компонент для создания отложенных задач для управления подписками.
 * Class ProductTicketsService
 * @package app\components\tickets
 */
class ProductTicketsService
{
	/**
	 * Создание тикета на подключение продукта абоненту.
	 * @param int $productId идентификатор продукта.
	 * @param int $abonentId идентификатор абонента.
	 * @return string идентификатор созданного тикета (джобы).
	 */
	public function enable(int $productId, int $abonentId): string
	{
		return Yii::$app->productTicketsQueue->push(new EnableProductJob($productId, $abonentId));
	}

	/**
	 * Создание тикета на отлючение продукта от абонента.
	 * @param int $productId идентификатор продукта.
	 * @param int $abonentId идентификатор абонента.
	 * @return string идентификатор созданного тикета (джобы).
	 */
	public function disable(int $productId, int $abonentId): string
	{
		return Yii::$app->productTicketsQueue->push(new DisableProductJob($productId, $abonentId));
	}

	/**
	 * Получение статуса обработки тикета.
	 * @param string $ticketId идентификатор тикета.
	 * @return TicketStatusInterface
	 */
	public function getTicketStatus(string $ticketId): TicketStatusInterface
	{
		$jobStatus = Yii::$app->productTicketsQueue->status($ticketId);
		if ($jobStatus === Queue::STATUS_WAITING) {
			$ticketStatus = new TicketStatusWaiting();
		} elseif ($jobStatus === Queue::STATUS_RESERVED) {
			$ticketStatus = new TicketStatusInProgress(
				DateHelper::createImmutableFromTimestamp(
					Yii::$app->productTicketsQueue->lastStatusPayload['reserved_at']
				)
			);
		} else {
			$ticketStatus = new TicketStatusDone(
				DateHelper::createImmutableFromTimestamp(
					Yii::$app->productTicketsQueue->lastStatusPayload['done_at']
				)
			);
		}

		return $ticketStatus;
	}
}