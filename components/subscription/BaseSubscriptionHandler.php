<?php
declare(strict_types = 1);

namespace app\components\subscription;

use app\models\products\Products;
use app\models\ticket\TicketProductSubscription;
use InvalidArgumentException;
use yii\base\Component;

/**
 * Class BaseSubscriptionHandler
 * @package app\components\subscription
 */
abstract class BaseSubscriptionHandler extends Component
{
	/**
	 * @var TicketProductSubscription|null тикет, в рамках которого выполняется подключение/отключение услуги.
	 */
	protected ?TicketProductSubscription $_ticket = null;

	/**
	 * Подключение подписки по продукту для заданного абонента.
	 * При успешном выполнении операции - фиксируем новый статус в журнале статусов.
	 * @param TicketProductSubscription $ticket
	 * @param bool $serviceCheck
	 * @return string
	 */
	public function connect(TicketProductSubscription $ticket, bool $serviceCheck = false): string
	{
		$this->_ticket = $ticket;
		if ($serviceCheck) {
			$this->serviceCheck();
			return '';
		}

		$this->beforeSubscribe();

		$expireDate = $this->connectOnPartner();

		$this->_ticket->relatedAbonentsToProducts->enable($expireDate);

		return $expireDate;
	}

	/**
	 * Отключение подписки по продукту для заданного абонента.
	 * При успешном выполнении операции - фиксируем новый статус в журнале статусов.
	 * @param TicketProductSubscription $ticket
	 */
	public function disable(TicketProductSubscription $ticket): void
	{
		$this->_ticket = $ticket;

		$this->disableOnPartner();

		$this->_ticket->relatedAbonentsToProducts->disable();
	}

	/**
	 * Реализация подключения подписки по продукту на стороне партнёра.
	 * @return string новая дата окончания подписки.
	 */
	abstract protected function connectOnPartner(): string;

	/**
	 * Данный метод будет вызываться в случае необходимости проверки возможности подключения подписки по абоненту.
	 * Подразумевается, что в случае непрохождения проверок, кидается exception.
	 */
	abstract protected function serviceCheck(): void;

	/**
	 * Для различных полезных штук перед непосредственным запросом на подписку
	 * (инициализация переиспользуемых параметров, сброс состояния при изменении входных параметров, etc.).
	 */
	protected function beforeSubscribe(): void {}

	/**
	 * Реализация отключения подписки по продукту на стороне партнёра.
	 * DEFAULT: ничего не отправляем партнеру, подписка просто протухнет, если мы ее принудительно не обновим.
	 */
	protected function disableOnPartner(): void {}

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