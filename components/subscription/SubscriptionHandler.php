<?php
declare(strict_types = 1);

namespace app\components\subscription;

use app\models\abonents\Abonents;
use app\models\billing_journal\BillingJournal;
use app\models\products\Products;
use app\useCases\SwitchOffProductCase;
use app\useCases\SwitchOnProductCase;
use InvalidArgumentException;
use yii\base\Component;
use yii\web\NotFoundHttpException;

/**
 * Class BaseSubscriptionHandler
 * @package app\components\subscription
 */
abstract class SubscriptionHandler extends Component
{
	/**
	 * @var Products продукт, по которому будет обрабатываться подписка.
	 */
	protected Products $_product;
	/**
	 * @var Abonents|null абонент, по которому будет обрабатываться подписка.
	 */
	protected ?Abonents $_abonent = null;
	/**
	 * @var BillingJournal|null
	 */
	protected ?BillingJournal $_billingJournalRecord = null;

	/**
	 * BaseSubscriptionHandler constructor.
	 * @param Products $product
	 * @param array $config
	 */
	public function __construct(Products $product, array $config = [])
	{
		parent::__construct($config);

		$this->_product = $product;
	}

	/**
	 * Подключение подписки по продукту для заданного абонента.
	 * При успешном выполнении операции - фиксируем новый статус в журнале статусов.
	 * @param int $abonentId идентификатор абонента.
	 * @param string $billingId идентификатор списания средств.
	 * @return string
	 * @throws NotFoundHttpException
	 */
	final public function provide(int $abonentId, string $billingId): string
	{
		$this->initAbonent($abonentId);
		$this->initBillingJournalRecord($billingId);

		$this->beforeSubscribe();

		$expireDate = $this->subscribe();

		$useCase = new SwitchOnProductCase();
		$useCase->execute($abonentId, $this->_product->id, $expireDate);

		return $expireDate;
	}

	/**
	 * Отключение подписки по продукту для заданного абонента.
	 * При успешном выполнении операции - фиксируем новый статус в журнале статусов.
	 * @param int $abonentId
	 * @throws NotFoundHttpException
	 */
	final public function revoke(int $abonentId): void
	{
		$this->initAbonent($abonentId);

		$this->unsubscribe();

		$useCase = new SwitchOffProductCase();
		$useCase->execute($abonentId, $this->_product->id);
	}

	/**
	 * Для различных полезных штук перед непосредственным запросом на подписку
	 * (инициализация переиспользуемых параметров, сброс состояния при изменении входных параметров, etc.).
	 */
	protected function beforeSubscribe(): void
	{
	}

	/**
	 * Реализация подключения подписки по продукту на стороне партнёра.
	 * @return string новая дата окончания подписки.
	 */
	abstract protected function subscribe(): string;

	/**
	 * Реализация отключения подписки по продукту на стороне партнёра.
	 */
	abstract protected function unsubscribe(): void;

	/**
	 * Инициализация абонента.
	 * @param int $abonentId
	 * @throws NotFoundHttpException
	 */
	private function initAbonent(int $abonentId): void
	{
		$this->_abonent = Abonents::findOne($abonentId);
		if (null === $this->_abonent) {
			throw new NotFoundHttpException("Не удалось определить абонента по ID $abonentId");
		}
	}

	/**
	 * Инициализация модели списания из журнала списаний.
	 * @param string $billingId
	 * @throws NotFoundHttpException
	 */
	private function initBillingJournalRecord(string $billingId): void
	{
		$this->_billingJournalRecord = BillingJournal::findOne($billingId);
		if (null === $this->_billingJournalRecord) {
			throw new NotFoundHttpException("Не удалось установить факт списания по ID $billingId");
		}
	}

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
				return new IviSubscriptionHandler($product);
			case 'vet-expert':
				return new VetExpertSubscriptionHandler($product);
			default:
				throw new InvalidArgumentException('Не удалось определить обработчик для продукта');
		}
	}
}