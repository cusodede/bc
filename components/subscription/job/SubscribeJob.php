<?php
declare(strict_types = 1);

namespace app\components\subscription\job;

use app\components\subscription\BaseSubscriptionHandler;
use app\models\ticket\TicketProductSubscription;
use Throwable;
use yii\queue\RetryableJobInterface;
use yii\web\NotFoundHttpException;

/**
 * Class SubscribeJob
 * @package app\components\subscription\job
 */
class SubscribeJob implements RetryableJobInterface
{
	private string $_ticketId;

	/**
	 * SubscribeJob constructor.
	 * @param string $ticketId
	 */
	public function __construct(string $ticketId)
	{
		$this->_ticketId = $ticketId;
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute($queue): void
	{
		$ticket = TicketProductSubscription::findOne($this->_ticketId);
		if (null === $ticket) {
			throw new NotFoundHttpException("Can't find the ticket by id $this->_ticketId");
		}

		$service = BaseSubscriptionHandler::createInstanceByProduct($ticket->relatedProduct);

		/** @noinspection BadExceptionsProcessingInspection not bad at all */
		try {
			$ticket->startStage(TicketProductSubscription::OPERATION_SERVICE_CHECK);
			//Делаем проверку на доступность подключения подписки.
			$service->connect($ticket, true);

			$ticket->startStage(TicketProductSubscription::OPERATION_BILLING_DEBIT);

			$ticket->startStage(TicketProductSubscription::OPERATION_CONNECT_ON_PARTNER);
			//Пытаемся непосредственно оформить подписку.
			$service->connect($ticket);

			$ticket->close();
		} catch (Throwable $e) {
			$ticket->markStageFailed($e);
			$ticket->close();

			throw $e;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTtr(): int
	{
		return 300;
	}

	/**
	 * {@inheritdoc}
	 */
	public function canRetry($attempt, $error): bool
	{
		return false;
	}
}