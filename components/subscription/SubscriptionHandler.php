<?php
declare(strict_types = 1);

namespace app\components\subscription;

use app\models\abonents\Abonents;
use app\models\products\EnumProductsStatuses;
use app\models\products\Products;
use app\useCases\ProductStatusCase;
use InvalidArgumentException;
use yii\base\Component;

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
	 * BaseSubscriptionHandler constructor.
	 * @param Products $product
	 * @param array $config
	 */
	public function __construct(Products $product, $config = [])
	{
		parent::__construct($config);

		$this->_product = $product;
	}

	/**
	 * Подключение подписки по продукту для заданного абонента.
	 * При успешном выполнии операции - фиксируем новый статус в журнале статусов.
	 * @param int $abonentId
	 */
	final public function enable(int $abonentId): void
	{
		$this->abonentInit($abonentId);

		$this->subscribe();
		$this->changeProductStatus($abonentId, EnumProductsStatuses::STATUS_ENABLED);
	}

	/**
	 * Отключение подписки по продукту для заданного абонента.
	 * При успешном выполнии операции - фиксируем новый статус в журнале статусов.
	 * @param int $abonentId
	 */
	final public function disable(int $abonentId): void
	{
		$this->abonentInit($abonentId);

		$this->unsubscribe();
		$this->changeProductStatus($abonentId, EnumProductsStatuses::STATUS_DISABLED);
	}

	/**
	 * Реализация подключения подписки по продукту на стороне партнёра.
	 */
	abstract protected function subscribe(): void;

	/**
	 * Реализация отключения подписки по продукту на стороне партнёра.
	 */
	abstract protected function unsubscribe(): void;

	/**
	 * Инициализация абонента.
	 * @param int $abonentId
	 * @throws InvalidArgumentException
	 */
	private function abonentInit(int $abonentId): void
	{
		$this->_abonent = Abonents::findOne($abonentId);
		if (null === $this->_abonent) {
			throw new InvalidArgumentException('Не удалось определить абонента.');
		}
	}

	/**
	 * Обновляем статус подписки по продукту для абонента в журнале статусов.
	 * @param int $abonentId
	 * @param int $statusId
	 */
	private function changeProductStatus(int $abonentId, int $statusId): void
	{
		$useCase = new ProductStatusCase();
		$useCase->update($abonentId, $this->_product->id, $statusId);
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