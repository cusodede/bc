<?php
declare(strict_types = 1);

namespace app\components\subscription\job;

use app\components\subscription\BaseSubscriptionHandler;
use app\models\ticket\TicketProductSubscription;
use Yii;
use yii\queue\RetryableJobInterface;
use yii\web\NotFoundHttpException;

/**
 * Class UnsubscribeJob
 * @package app\components\subscription\job
 */
class UnsubscribeJob implements RetryableJobInterface
{
	private string $_ticketId;

	/**
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
		$service->disable($ticket);

		$messages = [];
		$messages[] = '<i class="fas fa-fw fa-check text-success"></i> Подписка успешно отключена';
		$messages[] = 'stop';

		Yii::$app->cache->set('mvp', array_merge(Yii::$app->cache->get('mvp'), $messages));
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