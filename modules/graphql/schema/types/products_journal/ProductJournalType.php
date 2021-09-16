<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products_journal;

use app\models\abonents\Abonents;
use app\models\products\EnumProductsStatuses;
use app\models\products\Products;
use app\models\products\ProductsJournal;
use app\models\subscriptions\EnumSubscriptionTrialUnits;
use app\models\subscriptions\Subscriptions;
use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\definition\DateTimeType;
use app\modules\graphql\schema\types\abonents\AbonentType;
use app\modules\graphql\schema\types\products\ProductType;
use GraphQL\Type\Definition\Type;
use DateTimeImmutable;

/**
 * Описание типа, журнал подписок абонента.
 */
class ProductJournalType extends BaseObjectType
{
	/**
	 * {@inheritdoc}
	 */
	protected function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::string(),
					'description' => 'Идентификатор события журнала',
				],
				'abonent' => [
					'type' => AbonentType::type(),
					'resolve' => fn(ProductsJournal $productsJournal): ?Abonents => $productsJournal->relatedAbonent,
					'description' => 'Абонент',
				],
				'status_id' => [
					'type' => Type::int(),
				],
				'status' => [
					'type' => ProductJournalStatusType::type(),
					'resolve' => fn(ProductsJournal $productsJournal): ?array => static::enumResolve(
						EnumProductsStatuses::mapData(), $productsJournal->status_id
					),
				],
				'product' => [
					'type' => ProductType::type(),
					'resolve' => fn(ProductsJournal $productsJournal): ?Products => $productsJournal->relatedProduct,
				],
				'expire_date' => [
					'type' => DateTimeType::type(),
					'description' => 'Срок действия Y-m-d H:i:s',
					'resolve' => fn(ProductsJournal $productsJournal): ?DateTimeImmutable => DateTimeType::parseString($productsJournal->expire_date),
				],
				'created_at' => [
					'type' => DateTimeType::type(),
					'description' => 'Дата заведения Y-m-d H:i:s',
					'resolve' => fn(ProductsJournal $productsJournal): ?DateTimeImmutable => DateTimeType::parseString($productsJournal->created_at),
				],
			],
		]);
	}
}