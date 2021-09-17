<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products_journal;

use app\modules\graphql\components\BaseObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Тип для статусов журнала подключений.
 */
class ProductJournalStatusType extends BaseObjectType
{
	protected function __construct()
	{
		parent::__construct([
			'description' => 'Статусы истории подключений',
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор статуса',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование статуса',
				],
			],
		]);
	}
}