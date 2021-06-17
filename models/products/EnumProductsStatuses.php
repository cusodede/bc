<?php
declare(strict_types=1);

namespace app\models\products;

use yii\helpers\ArrayHelper;

/**
 * Class EnumProductsStatuses
 * @package app\models\products
 */
class EnumProductsStatuses
{
	public const STATUS_ENABLED = 1;
	public const STATUS_PROLONGATION = 2;
	public const STATUS_DISABLED = 3;

	public const STATUSES = [
		self::STATUS_ENABLED      => 'Подключено',
		self::STATUS_PROLONGATION => 'Продлено',
		self::STATUS_DISABLED     => 'Отключено',
	];

	public static function getStatusName(int $statusId): ?string
	{
		return ArrayHelper::getValue(self::STATUSES, $statusId);
	}
}