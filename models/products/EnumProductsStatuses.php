<?php
declare(strict_types = 1);

namespace app\models\products;

use app\components\helpers\Html;
use app\models\common\EnumTrait;

/**
 * Class EnumProductsStatuses
 * @package app\models\products
 */
class EnumProductsStatuses
{
	use EnumTrait;

	public const STATUS_ENABLED = 1;
	public const STATUS_RENEWED = 2;
	public const STATUS_CANCELED_NO_MONEY = 3;
	public const STATUS_CANCELED = 4;
	public const STATUS_DISABLED = 5;

	/**
	 * {@inheritdoc}
	 */
	public static function mapData(): array
	{
		return [
			self::STATUS_ENABLED => 'Подключение',
			self::STATUS_RENEWED => 'Продление',
			self::STATUS_CANCELED_NO_MONEY => 'Блокировка: недостаточно средств',
			self::STATUS_CANCELED => 'Блокировка: подписка отменена',
			self::STATUS_DISABLED => 'Отключение',
		];
	}

	/**
	 * @param int $statusId
	 * @return string
	 */
	public static function getBadge(int $statusId): string
	{
		$statusDesc = self::mapData()[$statusId];

		if (self::isInactive($statusId)) {
			return Html::badgeError($statusDesc);
		}
		if ($statusId === self::STATUS_ENABLED) {
			return Html::badgeSuccess($statusDesc);
		}
		return Html::badgeInfo($statusDesc);
	}

	/**
	 * @param int $statusId
	 * @return bool
	 */
	public static function isInactive(int $statusId): bool
	{
		return in_array($statusId, [self::STATUS_CANCELED_NO_MONEY, self::STATUS_CANCELED, self::STATUS_DISABLED], true);
	}
}