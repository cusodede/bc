<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\extended;

use app\models\products\EnumProductsPaymentPeriods;
use app\modules\graphql\schema\types\Types;
use app\modules\graphql\schema\types\TypeTrait;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Периодичность списания для продуктов
 * Class ProductPaymentPeriodType
 * @package app\modules\graphql\schema\types
 */
class ProductPaymentPeriodType extends ObjectType implements TypeInterface
{
	use TypeTrait;

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
			'type' => Type::listOf(Types::productPaymentPeriodType()),
			'resolve' => fn($paymentPeriod, array $args = []): ?array
				=> static::getListFromEnum(EnumProductsPaymentPeriods::mapData()),
		];
	}

	/**
	 * @return array
	 */
	public static function getOneOfType(): array
	{
		return [
			'type' => Types::productPaymentPeriodType(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'resolve' => fn($paymentPeriod, array $args = []): ?array
				=> static::getOneFromEnum(EnumProductsPaymentPeriods::mapData(), $args)
		];
	}
}