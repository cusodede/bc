<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\ivi;

/**
 * Коллекция подключенных подписок по абоненту.
 * Class PurchasesCollection
 * @package app\modules\api\connectors\ivi
 */
class PurchaseCollection
{
	/**
	 * @var PurchaseItem[]
	 */
	private array $_items = [];

	/**
	 * @param PurchaseItem $item
	 */
	public function addPurchase(PurchaseItem $item): void
	{
		$this->_items[] = $item;
	}

	/**
	 * @return PurchaseItem[]
	 */
	public function getItems(): array
	{
		return $this->_items;
	}
}