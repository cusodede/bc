<?php /** @noinspection ALL */
declare(strict_types = 1);

namespace app\components\validators\billing;

use yii\base\Model;

/**
 * Class Billing
 * Прототип API для обращения в биллинг
 */
class Billing extends Model {

	public const STATUS_INACTIVE = 0;
	public const STATUS_ACTIVE = 1;

	/**
	 * @param string $phone
	 * @return int
	 */
	public static function getStatus(string $phone):int {
		return self::STATUS_INACTIVE;
	}

	/**
	 * Оборот по номеру
	 * @param string $phone
	 * @param int $await
	 * @return float
	 */
	public static function getRevenue(string $phone, int $await):float {
		return random_int(0, 1000) / random_int(1, 100);
	}
}