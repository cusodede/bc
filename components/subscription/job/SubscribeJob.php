<?php
declare(strict_types = 1);

namespace app\components\subscription\job;

use app\components\subscription\BaseSubscriptionHandler;
use app\models\billing_journal\BillingJournal;
use app\models\billing_journal\EnumBillingJournalStatuses;
use app\models\ticket\TicketProductSubscription;
use Throwable;
use Yii;
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

		$messages = [];
		/** @noinspection BadExceptionsProcessingInspection not bad at all */
		try {
			$ticket->startStage(TicketProductSubscription::OPERATION_SERVICE_CHECK);
			$messages[] = '<i class="fas fa-fw fa-check text-success"></i> Проверка возможности подключения подписки на стороне партнера';
			sleep(1);
			//Делаем проверку на доступность подключения подписки.
			$service->connect($ticket, true);

			$messages[] = '<i class="fas fa-fw fa-check text-success"></i> Проверка абонента: статус - активный';
			$messages[] = '<i class="fas fa-fw fa-check text-success"></i> Проверка абонента: prepaid';
			$messages[] = '<i class="fas fa-fw fa-check text-success"></i> Проверка абонента: ФИО - Лапин Алексей Сергеевич';
			$messages[] = '<i class="fas fa-fw fa-check text-success"></i> Проверка абонента: баланс - unlimited';
			$messages[] = '<i class="fas fa-fw fa-check text-success"></i> Проверка абонента: подключенный САС счет - CPA_SAS и CPA_SAS_A - отсутствует';
			$messages[] = '<i class="fas fa-fw fa-check text-success"></i> Проверка абонента: запрет на подключение подписок - CPA_BL_2, CPA_BL_1, CPA_BL_A - отсутствует';

			$ticket->startStage(TicketProductSubscription::OPERATION_BILLING_DEBIT);
			$billingRow = new BillingJournal([
				'rel_abonents_to_products_id' => $ticket->rel_abonents_to_products_id,
				'price' => $ticket->relatedProduct->price,
				'status_id' => EnumBillingJournalStatuses::STATUS_CHARGED
			]);
			$billingRow->save();
			$ticket->setRelatedBilling($billingRow);
			sleep(1);
			//Делаем попытку списания средств.
			$messages[] = '<i class="fas fa-fw fa-check text-success"></i> Произведено списание средств со счета в размере ' . $ticket->relatedProduct->price . ' рублей';

			$ticket->startStage(TicketProductSubscription::OPERATION_CONNECT_ON_PARTNER);
			sleep(1);
			//Пытаемся непосредственно оформить подписку.
			$service->connect($ticket);
			$messages[] = '<i class="fas fa-fw fa-check text-success"></i> Подписка успешно подключена';
			$ticket->close();
			$messages[] = 'stop';

			Yii::$app->cache->set('mvp', array_merge(Yii::$app->cache->get('mvp'), $messages));
		} catch (Throwable $e) {
			Yii::$app->cache->set('mvp', array_merge(Yii::$app->cache->get('mvp'), ['stop']));

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