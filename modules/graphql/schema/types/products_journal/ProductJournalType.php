<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products_journal;

use app\models\products\Products;
use app\models\products\ProductsJournal;
use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\definition\DateTimeType;
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
				'phone' => [
					'type' => Type::string(),
					'resolve' => fn(ProductsJournal $productsJournal): ?string => $productsJournal->relatedAbonent->phone,
					'description' => 'Тут будет объект абонента, но пока просто строка',
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