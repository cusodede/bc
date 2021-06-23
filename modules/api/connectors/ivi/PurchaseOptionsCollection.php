<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\ivi;

/**
 * Коллекция опций подписок для создания/обновления подписки.
 * Class PurchasesOptionsCollection
 * @package app\modules\api\connectors\ivi
 */
class PurchaseOptionsCollection
{
	/**
	 * @var PurchaseOptionsItem[]
	 */
	private array $_items = [];

	/**
	 * @param PurchaseOptionsItem $item
	 */
	public function addPurchaseOptionsItem(PurchaseOptionsItem $item): void
	{
		$this->_items[] = $item;
	}

	/**
	 * @return PurchaseOptionsItem[]
	 */
	public function getItems(): array
	{
		return $this->_items;
	}
}