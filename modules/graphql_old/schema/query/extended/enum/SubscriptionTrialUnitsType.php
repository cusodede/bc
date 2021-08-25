<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query\extended\enum;

use app\models\subscriptions\EnumSubscriptionTrialUnits;
use app\modules\graphql\base\BaseQueryType;
use app\modules\graphql\data\EnumTypes;
use GraphQL\Type\Definition\Type;

/**
 * Class SubscriptionTrialUnitsType
 * @package app\modules\graphql\schema\query\extended\enum
 */
final class SubscriptionTrialUnitsType extends BaseQueryType
{
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
			'type' => Type::listOf(EnumTypes::subscriptionTrialUnitsType()),
			'description' => 'Возвращает список единиц триальных периодов',
			'resolve' => fn($paymentPeriod, array $args = []): ?array => self::getListFromEnum(EnumSubscriptionTrialUnits::mapData()),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getOneOfType(): array
	{
		return [
			'type' => EnumTypes::productPaymentPeriodType(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'description' => 'Возвращает единицу триального периода по ключу',
			'resolve' => fn($paymentPeriod, array $args = []): ?array => self::getOneFromEnum(EnumSubscriptionTrialUnits::mapData(), $args)
		];
	}
}