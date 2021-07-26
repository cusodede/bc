<?php
declare(strict_types = 1);

namespace app\modules\graphql\data;

use app\modules\graphql\schema\mutations\extended\PartnerMutationType;
use app\modules\graphql\schema\mutations\extended\ProductMutationType;
use app\modules\graphql\schema\mutations\extended\SubscriptionMutationType;
use app\modules\graphql\schema\mutations\MutationType;

/**
 * Class MutationTypes
 * @package app\modules\graphql\data
 */
class MutationTypes
{
	// Главный тип мутация
	private static ?MutationType $mutation = null;

	private static ?PartnerMutationType $partnerMutation = null;
	private static ?ProductMutationType $productMutation = null;
	private static ?SubscriptionMutationType $subscriptionMutation = null;

	/**
	 * Мутации
	 * @return MutationType
	 */
	public static function mutation(): MutationType
	{
		return static::$mutation ?: static::$mutation = new MutationType();
	}

	/**
	 * Мутации партнера
	 * @return PartnerMutationType
	 */
	public static function partnerMutation(): PartnerMutationType
	{
		return static::$partnerMutation ?: static::$partnerMutation = new PartnerMutationType();
	}

	/**
	 * Мутации продукта
	 * @return ProductMutationType
	 */
	public static function productMutation(): ProductMutationType
	{
		return static::$productMutation ?: static::$productMutation = new ProductMutationType();
	}

	/**
	 * Мутации подписок
	 * @return SubscriptionMutationType
	 */
	public static function subscriptionMutation(): SubscriptionMutationType
	{
		return static::$subscriptionMutation ?: static::$subscriptionMutation = new SubscriptionMutationType();
	}
}
