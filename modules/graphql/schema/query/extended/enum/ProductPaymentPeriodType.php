<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query\extended\enum;

use app\models\products\EnumProductsPaymentPeriods;
use app\modules\graphql\base\BaseQueryType;
use app\modules\graphql\data\EnumTypes;
use GraphQL\Type\Definition\Type;

/**
 * Class ProductPaymentPeriodType
 * @package app\modules\graphql\schema\query\extended\enum
 */
final class ProductPaymentPeriodType extends BaseQueryType
{
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор списания',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование списания',
				],
			],
		]);
	}

	/**
	 * @return array
	 */
	public static function getListOfType(): array
	{
		return [
			'type' => Type::listOf(EnumTypes::productPaymentPeriodType()),
			'description' => 'Возвращает список периодов списания абонентской платы по продукту',
			'resolve' => fn($paymentPeriod, array $args = []): ?array => self::getListFromEnum(EnumProductsPaymentPeriods::mapData()),
		];
	}

	/**
	 * @return array
	 */
	public static function getOneOfType(): array
	{
		return [
			'type' => EnumTypes::productPaymentPeriodType(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'description' => 'Возвращает единицу измерения периода списания абонентской платы по продукту',
			'resolve' => fn($paymentPeriod, array $args = []): ?array => self::getOneFromEnum(EnumProductsPaymentPeriods::mapData(), $args)
		];
	}
}