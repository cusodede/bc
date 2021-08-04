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
	 * @param bool $healthcheck
	 * @return string
	 */
	public function connect(TicketProductSubscription $ticket, bool $healthcheck = false): string
	{
		$this->_ticket = $ticket;
		if ($healthcheck) {
			$this->doHealthcheck();
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
	abstract protected function doHealthcheck(): void;

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
	 * @return IviSubscriptionHandler|VetExpertSubscriptionHandler
	 * @throws InvalidArgumentException
	 */
	public static function createInstanceByProduct(Products $product)
	{
		switch ($product->relatedPartner->name) {
			case 'ivi':
				return new IviSubscriptionHandler();
			case 'vet-expert':
				return new VetExpertSubscriptionHandler();
			default:
				throw new InvalidArgumentException('Не удалось определить обработчик для продукта');
		}
	}
}