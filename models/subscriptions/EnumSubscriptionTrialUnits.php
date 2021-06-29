<?php
declare(strict_types = 1);

namespace app\models\subscriptions;

use yii\helpers\ArrayHelper;
use Exception;

/**
 * Class EnumSubscriptionTrialUnits
 * @package app\models\subscriptions
 */
class EnumSubscriptionTrialUnits
{
	public const UNIT_DAYS = 1;
	public const UNIT_WEEK = 2;
	public const UNIT_MONTH = 3;

	public const UNITS = [
		self::UNIT_DAYS 	=> 'days',
		self::UNIT_WEEK 	=> 'week',
		self::UNIT_MONTH 	=> 'month',
	];

	/**
	 * @param string $unitId
	 * @return string|null
	 * @throws Exception
	 */
	public static function getStatusName(string $unitId): ?string
	{
		return ArrayHelper::getValue(self::UNITS, $unitId);
	}
}