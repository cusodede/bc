<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\ivi;

use yii\helpers\ArrayHelper;

/**
 * Класс для инкапсуляции логики взаимодействия с данными, полученными от ivi.
 * Class PurchasesOptionsHandler
 * @package app\modules\api\connectors\ivi
 */
class PurchaseOptionsHandler
{
	private array $_data;
	/**
	 * @var PurchaseCollection коллекция подключенных подписок по абоненту.
	 */
	private PurchaseCollection $_purchasesCollection;
	/**
	 * @var PurchaseOptionsCollection коллекция опций подписок для создания/обновления подписки.
	 */
	private PurchaseOptionsCollection $_purchasesOptionsCollection;

	/**
	 * PurchasesOptions constructor.
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$this->_data = $data;

		$this->initPurchasesCollection();
		$this->initPurchasesOptionsCollection();
	}

	public function getPurchases(): PurchaseCollection
	{
		return $this->_purchasesCollection;
	}

	public function getPurchasesOptions(): PurchaseOptionsCollection
	{
		return $this->_purchasesOptionsCollection;
	}

	private function initPurchasesCollection(): void
	{
		$this->_purchasesCollection = new PurchaseCollection();

		$data = ArrayHelper::remove($this->_data, 'purchases', []);
		foreach ($data as $itemData) {
			$this->_purchasesCollection->addPurchase(new PurchaseItem($itemData));
		}
	}

	private function initPurchasesOptionsCollection(): void
	{
		$this->_purchasesOptionsCollection = new PurchaseOptionsCollection();

		$data = ArrayHelper::remove($this->_data, 'purchase_options', []);
		foreach ($data as $itemData) {
			$this->_purchasesOptionsCollection->addPurchaseOptionsItem(new PurchaseOptionsItem($itemData));
		}
	}
}