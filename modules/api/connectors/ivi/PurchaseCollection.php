<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\ivi;

use app\common\CollectionInterface;
use Exception;

/**
 * Коллекция подключенных подписок по абоненту.
 * Class PurchasesCollection
 * @package app\modules\api\connectors\ivi
 */
class PurchaseCollection implements CollectionInterface
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

	/**
	 * {@inheritdoc}
	 */
	public function isEmpty(): bool
	{
		return [] === $this->_items;
	}

	/**
	 * @param int $id
	 * @return PurchaseItem|null
	 * @throws Exception
	 */
	public function extractById(int $id): ?PurchaseItem
	{
		foreach ($this->_items as $item) {
			if ($id === $item->getId()) {
				return $item;
			}
		}
		return null;
	}
}