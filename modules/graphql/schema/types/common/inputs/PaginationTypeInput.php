<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\common\inputs;

use app\modules\graphql\components\BaseInputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class PaginationTypeInput
 * Параметры совпадают с соответствующими атрибутами [[yii\data\Pagination::class]] - тип сделан совместимым с ним
 */
class PaginationTypeInput extends BaseInputObjectType {
	/**
	 * @inheritDoc
	 */
	public function __construct() {
		parent::__construct([
			'description' => 'Параметры пагинации',
			'fields' => [
				'page' => [
					'type' => Type::int(),
					'description' => 'The zero-based current page number.'
				],
				'pageSize' => [
					'type' => Type::int(),
					'description' => 'The number of items per page.'
				]
			]
		]);
	}
}