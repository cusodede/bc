<?php
declare(strict_types = 1);

namespace app\components\subscription;

use app\models\abonents\RelAbonentsToProducts;
use app\models\products\EnumProductsStatuses;
use app\models\products\ProductsJournal;

/**
 * Всё же пусть логика смены статусов будет инкапсулирована в отдельном сервисе.
 */
class ProductStatusChangeCase
{
	private static ?self $_instance = null;

	/**
	 * Singleton use.
	 */
	private function __construct()
	{
	}

	/**
	 * @return static
	 */
	public static function getInstance(): static
	{
		return self::$_instance ??= new static();
	}

	/**
	 * Запись в журнал информации о продлении подписки.
	 * @param int $productId
	 * @param int $abonentId
	 * @param string $expireDate
	 */
	public function activate(int $productId, int $abonentId, string $expireDate): void
	{
		$relation = RelAbonentsToProducts::findOne(['product_id' => $productId, 'abonent_id' => $abonentId]);

		$config = ['expire_date' => $expireDate];
		if ((null === $relation->relatedLastProductsJournal) || $relation->relatedLastProductsJournal->isDisabled) {
			$config['status_id'] = EnumProductsStatuses::STATUS_ENABLED;
		} else {
			$config['status_id'] = EnumProductsStatuses::STATUS_RENEWED;
		}

		$relation->relatedLastProductsJournal = new ProductsJournal($config);
	}

	/**
	 * Запись в журнал информации о деактивации подписки.
	 * Предусмотрено несколько вариантов блокировки подписки (недостаточно денег, запрос от клиента и т.д.).
	 * @param int $productId
	 * @param int $abonentId
	 * @param int $statusId
	 */
	public function deactivate(int $productId, int $abonentId, int $statusId = EnumProductsStatuses::STATUS_DISABLED): void
	{
		$relation = RelAbonentsToProducts::findOne(['product_id' => $productId, 'abonent_id' => $abonentId]);

		$config = ['status_id' => $statusId, 'expire_date' => $relation->relatedLastProductsJournal->expire_date];

		$relation->relatedLastProductsJournal = new ProductsJournal($config);
	}
}