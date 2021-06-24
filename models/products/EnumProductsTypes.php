<?php
declare(strict_types = 1);

namespace app\models\products;

use yii\helpers\ArrayHelper;
use Exception;

/**
 * Class EnumProductsTypes
 * @package app\models\products
 */
class EnumProductsTypes
{
	public const TYPE_SUBSCRIPTION = 1;
	public const TYPE_BUNDLE = 2;

	public const PRODUCTS_TYPES = [
		self::TYPE_SUBSCRIPTION => 'Подписка',
		self::TYPE_BUNDLE       => 'Бандл',
	];

	/**
	 * @param int $typeId
	 * @return string|null
	 * @throws Exception
	 */
	public static function getTypeName(int $typeId): ?string
	{
		return ArrayHelper::getValue(self::PRODUCTS_TYPES, $typeId);
	}
}