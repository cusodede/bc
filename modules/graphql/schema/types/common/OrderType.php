<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\common;

use app\modules\graphql\components\BaseObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class OrderType
 * Класс типа данных о сортировке.
 */
class OrderType extends BaseObjectType {
	public const DIRECTION_STRING_ASC = 'ASC';
	public const DIRECTION_STRING_DESC = 'DESC';

	public function __construct() {
		parent::__construct([
			'description' => 'Данные о сортировки',
			'fields' => [
				'field' => [
					'type' => Type::string(),
					'description' => 'Поле для сортировки'
				],
				'ascending' => [
					'type' => Type::boolean(),
					'description' => 'True (default) for ascending sorting, false for descending'
				],
				'directionString' => [
					'type' => Type::string(),
					'description' => 'String sorting representation'
				]
			]
		]);
	}
}