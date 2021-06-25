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
	public const UNIT_DAYS = 'days';
	public const UNIT_WEEK = 'week';
	public const UNIT_MONTH = 'month';

	public const UNITS = [
		self::UNIT_DAYS  => 'days',
		self::UNIT_WEEK  => 'week',
		self::UNIT_MONTH  => 'month',
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