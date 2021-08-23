<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products;

use app\models\partners\Partners;
use app\models\products\Products;
use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\definition\DateTimeType;
use app\modules\graphql\schema\types\partners\PartnerType;
use GraphQL\Type\Definition\Type;
use DateTimeImmutable;

/**
 * Class ProductType
 * @package app\modules\graphql\schema\types\products
 */
class ProductType extends BaseObjectType
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
					'description' => 'Идентификатор продукта',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование продукта',
				],
				'price' => [
					'type' => Type::float(),
					'description' => 'Цена',
				],
				'description' => [
					'type' => Type::string(),
					'description' => 'Краткое описание',
				],
				'ext_description' => [
					'type' => Type::string(),
					'description' => 'Полное описание',
				],
				'start_date' => [
					'type' => DateTimeType::dateTime(),
					'description' => 'Начало действия Y-m-d H:i:s',
					'resolve' => fn(Products $products): ?DateTimeImmutable => DateTimeType::parseString($products->start_date)
				],
				'end_date' => [
					'type' => DateTimeType::dateTime(),
					'description' => 'Конец действия Y-m-d H:i:s',
					'resolve' => fn(Products $products): ?DateTimeImmutable => DateTimeType::parseString($products->end_date)
				],
				'type_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор типа',
				],
				'partner_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор партнёра',
				],
				'partner' => [
					'type' => PartnerType::type(),
					'description' => 'Партнёр',
					'resolve' => fn(Products $product): ?Partners => $product->relatedPartner,
				],
				'trial_count' => [
					'type' => Type::int(),
					'description' => 'Триальный период',
					'resolve' => fn(Products $product): int => $product->relatedInstance->trial_count ?? 0,
				],
				'created_at' => [
					'type' => DateTimeType::dateTime(),
					'description' => 'Дата создания Y-m-d H:i:s',
					'resolve' => fn(Products $products): ?DateTimeImmutable => DateTimeType::parseString($products->created_at),
				],
			],
		]);
	}
}