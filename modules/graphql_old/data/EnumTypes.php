<?php
declare(strict_types = 1);

namespace app\modules\graphql\data;

use app\modules\graphql\schema\query\extended\enum\ProductPaymentPeriodType;
use app\modules\graphql\schema\query\extended\enum\ProductTypesType;
use app\modules\graphql\schema\query\extended\enum\SubscriptionTrialUnitsType;

/**
 * Class EnumTypes
 * @package app\modules\graphql\data
 */
class EnumTypes
{

	private static ?ProductTypesType $productType = null;
	private static ?ProductPaymentPeriodType $productPayment = null;
	private static ?SubscriptionTrialUnitsType $trialUnit = null;

	/**
	 * Платежный период у продуктов
	 * @return ProductPaymentPeriodType
	 */
	public static function productPaymentPeriodType(): ProductPaymentPeriodType
	{
		return static::$productPayment ?: static::$productPayment = new ProductPaymentPeriodType();
	}

	/**
	 * Единицы измерения триального периода
	 * @return SubscriptionTrialUnitsType
	 */
	public static function subscriptionTrialUnitsType(): SubscriptionTrialUnitsType
	{
		return static::$trialUnit ?: static::$trialUnit = new SubscriptionTrialUnitsType();
	}

	/**
	 * Типы продуктов
	 * @return ProductTypesType
	 */
	public static function productTypesType(): ProductTypesType
	{
		return static::$productType ?: static::$productType = new ProductTypesType();
	}
}
