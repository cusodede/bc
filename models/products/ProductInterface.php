<?php
declare(strict_types = 1);

namespace app\models\products;

/**
 * Interface MerchInterface
 * Описывает любой товар, передаваемый в продажу
 *
 * @property int $id
 */
interface ProductInterface {
	const EVENT_CONFIRM = 'Confirm';
	const EVENT_SELL = 'Sell';

	/**
	 * Регистрация продажи товара
	 * @return null|bool Успешность операции, null - отсутствие подтверждения.
	 */
	public function doSell():?bool;


}