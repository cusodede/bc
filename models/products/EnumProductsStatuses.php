<?php
declare(strict_types = 1);

namespace app\models\products;

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
	public const STATUS_DISABLED = 3;

	/**
	 * {@inheritdoc}
	 */
	public static function mapData(): array
	{
		return [
			self::STATUS_ENABLED  => 'Подключено',
			self::STATUS_RENEWED  => 'Продлено',
			self::STATUS_DISABLED => 'Отключено',
		];
	}
}