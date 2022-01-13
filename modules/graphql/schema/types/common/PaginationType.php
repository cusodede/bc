<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\common;

use app\modules\graphql\components\BaseObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class PaginationType
 * Параметры совпадают с атрибутами [[yii\data\Pagination::class]] - тип сделан совместимым с ним
 */
class PaginationType extends BaseObjectType {
	public function __construct() {
		parent::__construct([
			'description' => 'Данные о пагинации',
			'fields' => [
				'limit' => [
					'type' => Type::int(),
					'description' => 'The limit of the data.'
				],
				'offset' => [
					'type' => Type::int(),
					'description' => 'The offset of the data.'
				],
				'totalCount' => [
					'type' => Type::int(),
					'description' => 'Total number of possible data models.'
				],
				'page' => [
					'type' => Type::int(),
					'description' => 'The zero-based current page number.'
				],
				'pageSize' => [
					'type' => Type::int(),
					'description' => 'The number of items per page.'
				],
				'pageCount' => [
					'type' => Type::int(),
					'description' => 'Number of pages.'
				],
			]
		]);
	}
}