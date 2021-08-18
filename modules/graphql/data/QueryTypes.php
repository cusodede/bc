<?php
declare(strict_types = 1);

namespace app\modules\graphql\data;

use app\modules\graphql\schema\mutations\extended\PartnerMutationType;
use app\modules\graphql\schema\mutations\extended\ProductMutationType;
use app\modules\graphql\schema\mutations\extended\SubscriptionMutationType;
use app\modules\graphql\schema\query\extended\UserType;
use app\modules\graphql\schema\query\extended\enum\FormPartnersType;
use app\modules\graphql\schema\query\extended\enum\FormSubscriptionsType;
use app\modules\graphql\schema\query\extended\PartnerCategoryType;
use app\modules\graphql\schema\query\extended\PartnerType;
use app\modules\graphql\schema\query\extended\ProductType;
use app\modules\graphql\schema\query\extended\SubscriptionType;
use app\modules\graphql\schema\query\QueryType;
use Closure;
use yii\helpers\ArrayHelper;

/**
 * Class QueryTypes
 * @package app\modules\graphql\schema\types
 */
class QueryTypes
{
	// Главный тип Query
	private static ?QueryType $query = null;

	// Типы для наших сущностей
	private static ?PartnerType $partner = null;
	private static ?PartnerCategoryType $partnerCategory = null;
	private static ?ProductType $product = null;
	private static ?SubscriptionType $subscription = null;
	private static ?UserType $currentUser = null;

	/**
	 * @return QueryType
	 */
	public static function query(): QueryType
	{
		return static::$query ?: static::$query = new QueryType();
	}

	/**
	 * Запросы партнера.
	 * @return PartnerType
	 */
	public static function partner(): PartnerType
	{
		return static::$partner ?: static::$partner = new PartnerType();
	}

	/**
	 * Категории партнеров.
	 * @return PartnerCategoryType
	 */
	public static function partnerCategory(): PartnerCategoryType
	{
		return static::$partnerCategory ?: static::$partnerCategory = new PartnerCategoryType();
	}

	/**
	 * Продукты.
	 * @return ProductType
	 */
	public static function product(): ProductType
	{
		return static::$product ?: static::$product = new ProductType();
	}

	/**
	 * Подписки.
	 * @return SubscriptionType
	 */
	public static function subscription(): SubscriptionType
	{
		return static::$subscription ?: static::$subscription = new SubscriptionType();
	}

	/**
	 * Текущий пользователь.
	 * @return UserType
	 */
	public static function user(): UserType
	{
		return static::$currentUser ?: static::$currentUser = new UserType();
	}

	/**
	 * Enum для генерации формы партнёров.
	 * @return Closure
	 */
	public static function formPartners(): Closure
	{
		return static fn(): FormPartnersType => new FormPartnersType((new PartnerMutationType())->getArgs());
	}

	/**
	 * Enum для генерации формы подписок.
	 * @return Closure
	 */
	public static function formSubscriptions(): Closure
	{
		return static fn(): FormSubscriptionsType => new FormSubscriptionsType(
			ArrayHelper::merge(
				(new ProductMutationType())->getArgs(),
				(new SubscriptionMutationType())->getArgs(),
			)
		);
	}
}
