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
	public const ID_SUBSCRIPTION = 1;
	public const ID_BUNDLE = 2;

	public const PRODUCTS_TYPES = [
		self::ID_SUBSCRIPTION => 'Подписка',
		self::ID_BUNDLE       => 'Бандл',
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