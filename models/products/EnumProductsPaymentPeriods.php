<?php
declare(strict_types = 1);

namespace app\models\products;

use app\models\common\EnumTrait;

/**
 * Class EnumProductsPaymentPeriods
 * @package app\models\products
 */
class EnumProductsPaymentPeriods
{
	use EnumTrait;

	public const TYPE_ONE_TIME = 1;
	public const TYPE_DAILY = 2;
	public const TYPE_MONTHLY = 3;

	/**
	 * {@inheritdoc}
	 */
	public static function mapData(): array
	{
		return [
			self::TYPE_ONE_TIME => 'Разовое списание',
			self::TYPE_DAILY => 'Ежедневное',
			self::TYPE_MONTHLY => 'Ежемесячное'
		];
	}
}