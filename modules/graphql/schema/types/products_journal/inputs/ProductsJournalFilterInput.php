<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products_journal\inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Фильтрация для истории операций
 */
class ProductsJournalFilterInput extends InputObjectType
{
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'searchProductId' => [
					'type' => Type::int(),
					'description' => 'Идентификатор продукта, для поиска',
				],
				'partnerId' => [
					'type' => Type::int(),
					'description' => 'Идентификатор партнёра',
				],
				'statusIds' => [
					'type' => Type::listOf(Type::int()),
					'description' => 'Массив идентификаторов статусов',
				],
			]
		]);
	}
}