<?php
declare(strict_types = 1);

namespace app\models\reward;

use app\models\reward\active_record\RewardsAR;

/**
 * Class Rewards
 * Логика над вознаграждениями.
 *
 * За что было вознаграждение? Я что-то продал
 */
class Rewards extends RewardsAR {

	/*Прототипирую причины начисления наград*/
	const REASON_SALE_REGISTERED = 1;//За продажу товара
	const REASON_SALE_CONFIRMED = 2;//Продажа активна
	const REASON_FRAUD = 3;//Фродовая продажа

	/*Статусы вознаграждений*/
	const STATUS_APPLY = 1;//начислено (может быть выведено)
	const STATUS_WAIT = 2;//начисление ждёт события, после которого будет начислено
	const STATUS_HOLD = 3;//начисление заморожено (не знаем, сколько начислить, заподозрили фрод, etc)
	const STATUS_ERROR = 4;//ошибочное начисление
	/*Инициирующие операции*/
	const OPERATION_SELL = 1;

	/**
	 * @param array $rewards
	 */
	public static function register(array $rewards) {
	}

}