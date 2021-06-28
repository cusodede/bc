<?php
declare(strict_types=1);

namespace app\models\products;

use yii\helpers\ArrayHelper;
use Exception;

/**
 * Class EnumProductsStatuses
 * @package app\models\products
 */
class EnumProductsStatuses
{
	public const STATUS_ENABLED = 1;
	public const STATUS_RENEWED = 2;
	public const STATUS_DISABLED = 3;

	public const STATUSES = [
		self::STATUS_ENABLED  => 'Подключено',
		self::STATUS_RENEWED  => 'Продлено',
		self::STATUS_DISABLED => 'Отключено',
	];

	/**
	 * @param int $statusId
	 * @return string|null
	 * @throws Exception
	 */
	public static function getStatusName(int $statusId): ?string
	{
		return ArrayHelper::getValue(self::STATUSES, $statusId);
	}
}