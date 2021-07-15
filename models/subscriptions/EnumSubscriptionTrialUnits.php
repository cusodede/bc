<?php
declare(strict_types = 1);

namespace app\models\subscriptions;

use app\models\common\EnumTrait;

/**
 * Class EnumSubscriptionTrialUnits
 * @package app\models\subscriptions
 */
class EnumSubscriptionTrialUnits
{
	use EnumTrait;

	public const UNIT_DAYS = 1;
	public const UNIT_WEEK = 2;
	public const UNIT_MONTH = 3;

	/**
	 * {@inheritdoc}
	 */
	public static function mapData(): array
	{
		return [
			self::UNIT_DAYS => 'День',
			self::UNIT_WEEK => 'Неделя',
			self::UNIT_MONTH => 'Месяц',
		];
	}
}