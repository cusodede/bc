<?php
declare(strict_types = 1);

namespace app\components\tickets;

use app\components\subscription\job\SubscribeJob;
use app\components\subscription\job\UnsubscribeJob;
use app\models\ticket\TicketSubscription;
use app\models\ticket\TicketSubscriptionParams;
use app\modules\api\resources\formatters\TicketSubscriptionFormatter;
use Throwable;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Компонент для создания отложенных задач для управления подписками.
 * Class ProductTicketsService
 * @package app\components\tickets
 */
class ProductTicketsService
{
	/**
	 * Создание тикета на подключение подписки.
	 * @param int $productId идентификатор продукта.
	 * @param int $abonentId идентификатор абонента.
	 * @param int|null $userId
	 * @return string идентификатор созданного тикета (джобы).
	 * @throws Throwable
	 */
	public function createSubscribeTicket(int $productId, int $abonentId, ?int $userId = null): string
	{
		$params = new TicketSubscriptionParams([
			'productId' => $productId,
			'abonentId' => $abonentId,
			'action' => TicketSubscription::ACTION_CONNECT_SUBSCRIPTION,
			'createdBy' => $userId ?? Yii::$app->user->id
		]);

		$ticket = TicketSubscription::createTicket($params);

		Yii::$app->productTicketsQueue->push(new SubscribeJob($ticket->id));

		return $ticket->id;
	}

	/**
	 * Создание тикета на отключение подписки.
	 * @param int $productId идентификатор продукта.
	 * @param int $abonentId идентификатор абонента.
	 * @param int|null $userId
	 * @return string идентификатор созданного тикета (джобы).
	 * @throws Throwable
	 */
	public function createUnsubscribeTicket(int $productId, int $abonentId, ?int $userId = null): string
	{
		$params = new TicketSubscriptionParams([
			'productId' => $productId,
			'abonentId' => $abonentId,
			'action' => TicketSubscription::ACTION_DISABLE_SUBSCRIPTION,
			'createdBy' => $userId ?? Yii::$app->user->id
		]);

		$ticket = TicketSubscription::createTicket($params);

		Yii::$app->productTicketsQueue->push(new UnsubscribeJob($ticket->id));

		return $ticket->id;
	}

	/**
	 * Получение статуса обработки тикета.
	 * @param string $ticketId идентификатор тикета.
	 * @return array
	 * @throws NotFoundHttpException
	 */
	public static function getTicketStatus(string $ticketId): array
	{
		$ticket = TicketSubscription::findOne($ticketId);
		if (null === $ticket) {
			throw new NotFoundHttpException("Can't find the ticket by id $ticketId");
		}

		return (new TicketSubscriptionFormatter())->format($ticket);
	}
}