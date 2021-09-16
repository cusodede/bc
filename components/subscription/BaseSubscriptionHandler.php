<?php
declare(strict_types = 1);

namespace app\components\subscription;

use app\components\helpers\DateHelper;
use app\models\products\Products;
use app\models\ticket\TicketSubscription;
use yii\base\Component;
use InvalidArgumentException;
use Throwable;
use yii\db\StaleObjectException;

/**
 * Class BaseSubscriptionHandler
 * @package app\components\subscription
 */
abstract class BaseSubscriptionHandler extends Component
{
	/**
	 * @var TicketSubscription|null тикет, в рамках которого выполняется подключение/отключение услуги.
	 */
	protected ?TicketSubscription $_ticket = null;

	/**
	 * Подключение подписки по продукту для заданного абонента.
	 * При успешном выполнении операции - фиксируем новый статус в журнале статусов.
	 * @param TicketSubscription $ticket
	 * @return string дата истечения срока действия подписки.
	 * @throws StaleObjectException
	 * @throws Throwable
	 */
	public function activate(TicketSubscription $ticket): string
	{
		$this->_ticket = $ticket;

		if (($expireDate = $ticket->findLastProductJournal()?->expire_date) > DateHelper::lcDate()) {
			//Если абонент повторно активирует подписку, у которой еще не истек срок действия с момента последнего подключения,
			//то просто фиксируем в журнале статус подключения с текущей датой окончания подписки.
			$ticket->close();
		} else {
			/** @noinspection BadExceptionsProcessingInspection not bad at all */
			try {
				$ticket->updateStage(TicketSubscription::STAGE_CODE_ABONENT_VERIFICATION);
				//Делаем проверку на доступность подключения подписки.

				$ticket->updateStage(TicketSubscription::STAGE_CODE_SERVICE_VERIFICATION);
				//Делаем проверку на доступность подключения подписки.
//				$this->verifySubscription();TODO не забыть убрать после тестирования

				$ticket->updateStage(TicketSubscription::STAGE_CODE_BILLING_DEBIT);
				//Делаем попытку списания средств.

				$ticket->updateStage(TicketSubscription::STAGE_CODE_CONNECT_ON_PARTNER);
				//Пытаемся непосредственно оформить подписку.
//				$expireDate = $this->activateOnPartner();
				$expireDate = date_create('+ 1 month')->format('Y-m-d H:i:s');//TODO не забыть убрать после тестирования

				$ticket->close();
			} catch (Throwable $e) {
				$ticket->markStageFailed($e);
				$ticket->close();

				throw $e;
			}
		}

		ProductStatusChangeCase::getInstance()->activate(
			$ticket->relatedProduct->id,
			$ticket->relatedAbonent->id,
			$expireDate
		);

		return $expireDate;
	}

	/**
	 * Отключение подписки по продукту для заданного абонента.
	 * При успешном выполнении операции - фиксируем новый статус в журнале статусов.
	 * @param TicketSubscription $ticket
	 * @return string
	 */
	public function deactivate(TicketSubscription $ticket): string
	{
		$this->_ticket = $ticket;

		$this->deactivateOnPartner();

		$ticket->close();

		ProductStatusChangeCase::getInstance()->deactivate(
			$ticket->relatedProduct->id,
			$ticket->relatedAbonent->id
		);

		return '';
	}

	/**
	 * Данный метод будет вызываться в случае необходимости проверки возможности подключения подписки по абоненту.
	 * Подразумевается, что в случае непрохождения проверок, кидается exception.
	 */
	abstract protected function verifySubscription(): void;

	/**
	 * Реализация подключения подписки по продукту на стороне партнёра.
	 * @return string новая дата окончания подписки.
	 */
	abstract protected function activateOnPartner(): string;

	/**
	 * Реализация отключения подписки по продукту на стороне партнёра.
	 * DEFAULT: ничего не отправляем партнеру, подписка просто протухнет, если мы ее принудительно не обновим.
	 */
	protected function deactivateOnPartner(): void
	{
	}

	/**
	 * Создание обработчика для управления подключением/отключением подписок на продукты.
	 * @param Products $product
	 * @return BaseSubscriptionHandler
	 */
	public static function createInstanceByProduct(Products $product): BaseSubscriptionHandler
	{
		switch ($product->relatedPartner->name) {
			case 'IVI':
				return new IviSubscriptionHandler();
			case 'VetExpert':
				return new VetExpertSubscriptionHandler();
			default:
				throw new InvalidArgumentException('Не удалось определить обработчик для продукта');
		}
	}
}