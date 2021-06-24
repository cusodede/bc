<?php
declare(strict_types = 1);

namespace app\models\products;

use yii\helpers\ArrayHelper;
use Exception;

/**
 * Class EnumProductsPaymentPeriods
 * @package app\models\products
 */
class EnumProductsPaymentPeriods
{
	public const TYPE_MONTHLY = 1;
	public const TYPE_DAILY = 2;
	public const TYPE_ONE_TIME = 3;

	public const PAYMENT_PERIOD_TYPES = [
		self::TYPE_MONTHLY  => 'Ежемесячно',
		self::TYPE_DAILY    => 'Ежедневно',
		self::TYPE_ONE_TIME => 'Разовое списание',
	];

	/**
	 * @param int $typeId
	 * @return string|null
	 * @throws Exception
	 */
	public static function getTypeName(int $typeId): ?string
	{
		return ArrayHelper::getValue(self::PAYMENT_PERIOD_TYPES, $typeId);
	}
}