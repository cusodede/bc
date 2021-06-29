<?php
declare(strict_types = 1);

namespace app\models\products;

use app\models\common\EnumTrait;

/**
 * Class EnumProductsTypes
 * @package app\models\products
 */
class EnumProductsTypes
{
	use EnumTrait;

	public const TYPE_SUBSCRIPTION = 1;
	public const TYPE_BUNDLE = 2;

	/**
	 * {@inheritdoc}
	 */
	public static function mapData(): array
	{
		return [
			self::TYPE_SUBSCRIPTION => 'Подписка',
			self::TYPE_BUNDLE       => 'Бандл',
		];
	}
}