<?php
declare(strict_types = 1);

namespace app\models\reward;

use app\models\products\ProductsInterface;
use app\models\reward\active_record\RewardsAR;
use app\models\reward\config\RewardsOperationsConfig;
use app\models\sys\users\Users;
use Throwable;

/**
 * Class Rewards
 * Логика над вознаграждениями.
 *
 * За что было вознаграждение? Я что-то продал
 *
 * @property null|ProductsInterface $relatedProducts Связанный проданный продукта
 * @property-read null|RewardsOperationsConfig $relatedOperation Конфигурация статусов операций
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
	public const STATUS_PAID = 5;//выведен в налик
	public const STATUS_DENY = 6;//Отказ в начислении

	/**
	 * @return array[]
	 */
	public static function status_config():array {
		return [
			self::STATUS_APPLY => [
				'name' => 'Зачислен',
				'initial' => false,
				'finishing' => false,
				'next' => [],
				'allowed' => true

			],
			self::STATUS_WAIT => [
				'name' => 'Ожидается',
				'initial' => false,
				'finishing' => false,
				'next' => [],
				'allowed' => static function(Rewards $model, Users $user):bool {
					return true;
				}
			],
			self::STATUS_HOLD => [
				'name' => 'Заморожено',
				'initial' => false,
				'finishing' => false,
				'next' => [],
				'color' => '#ff0000'
			],
			self::STATUS_ERROR => [
				'name' => 'Ошибочное начисление',
				'initial' => false,
				'finishing' => true,
				'next' => [],
				'color' => '#ff0000'
			],
			self::STATUS_PAID => [
				'name' => 'Выведен',
				'initial' => false,
				'finishing' => true,
				'next' => [],
				'color' => '#ff0000'
			],
			self::STATUS_DENY => [
				'name' => 'Отказано',
				'initial' => false,
				'finishing' => true,
				'next' => [],
				'color' => '#ff0000'
			],
		];
	}

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
	 * Ну, допустим
	 * @param ProductsInterface $product
	 */
	public function setRelatedProducts(ProductsInterface $product):void {
		$this->product_id = $product->id;
		$this->product_type = $product->type;
	}

	/**
	 * @return null|RewardsOperationsConfig
	 * @throws Throwable
	 */
	public function getRelatedOperation():?RewardsOperationsConfig {
		return RewardsOperationsConfig::loadModel($this->operation);
	}

}