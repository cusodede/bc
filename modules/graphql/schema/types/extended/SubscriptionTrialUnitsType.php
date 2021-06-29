<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\extended;

use app\models\subscriptions\EnumSubscriptionTrialUnits;
use app\modules\graphql\schema\types\Types;
use app\modules\graphql\schema\types\TypeTrait;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class SubscriptionTrialUnitsType
 * @package app\modules\graphql\schema\types\extended
 */
class SubscriptionTrialUnitsType extends ObjectType
{
	use TypeTrait;

	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор единицы измерения',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование единицы измерения',
				],
			],
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getListOfType(): array
	{
		return [
			'type' => Type::listOf(Types::subscriptionTrialUnitsType()),
			'resolve' => fn($paymentPeriod, array $args = []): ?array
				=> static::getListFromEnum(EnumSubscriptionTrialUnits::mapData()),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getOneOfType(): array
	{
		return [
			'type' => Types::productPaymentPeriodType(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'resolve' => fn($paymentPeriod, array $args = []): ?array
				=> static::getOneFromEnum(EnumSubscriptionTrialUnits::mapData(), $args)
		];
	}
}