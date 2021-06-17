<?php
declare(strict_types = 1);

namespace app\models\reward;

use app\models\products\ProductsInterface;
use app\models\reward\active_record\RewardsAR;

/**
 * Class Rewards
 * Логика над вознаграждениями.
 *
 * За что было вознаграждение? Я что-то продал
 *
 * @property null|ProductsInterface $relatedProducts Связанный проданный продукта
 */
class Rewards extends RewardsAR {

	/*Прототипирую причины начисления наград*/
	public const REASON_SALE_REGISTERED = 1;//За продажу товара
	public const REASON_SALE_CONFIRMED = 2;//Продажа активна
	public const REASON_FRAUD = 3;//Фродовая продажа

	/*Статусы вознаграждений*/
	public const STATUS_APPLY = 1;//начислено (может быть выведено)
	public const STATUS_WAIT = 2;//начисление ждёт события, после которого будет начислено
	public const STATUS_HOLD = 3;//начисление заморожено (не знаем, сколько начислить, заподозрили фрод, etc)
	public const STATUS_ERROR = 4;//ошибочное начисление
	/*Инициирующие операции*/
	public const OPERATION_SELL = 1;

	/**
	 * @param self[] $rewards
	 */
	public static function register(array $rewards):void {
		foreach ($rewards as $reward) {
			$reward->save();
		}
	}

	/**
	 * @return string[]
	 */
	public static function reasons():array {
		return [
			self::REASON_SALE_REGISTERED => 'Товар продан',
			self::REASON_SALE_CONFIRMED => 'Подтверждение продажи',
			self::REASON_FRAUD => 'Подозрительная операция'
		];
	}

	/**
	 * @return string[]
	 */
	public static function statuses():array {
		return [
			self::STATUS_APPLY => 'Начислено',
			self::STATUS_WAIT => 'Ожидается',
			self::STATUS_HOLD => 'Заморожено',
			self::STATUS_ERROR => 'Ошибочное начисление'
		];
	}

	/**
	 * Ну, допустим
	 * @param ProductsInterface $product
	 */
	public function setRelatedProducts(ProductsInterface $product):void {
		$this->product_id = $product->id;
		$this->product_type = $product->type;
	}

}