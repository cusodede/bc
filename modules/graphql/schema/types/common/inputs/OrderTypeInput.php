<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\common\inputs;

use app\modules\graphql\components\BaseInputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class OrderTypeInput
 * Тип данных для ввода сортировки.
 * Мы разрешаем сортировать только по одному полю, для простоты и совместимости
 */
class OrderTypeInput extends BaseInputObjectType {
	/**
	 * @inheritDoc
	 */
	public function __construct() {
		parent::__construct([
			'description' => 'Параметры сортировки',
			'fields' => [
				'field' => [
					'type' => Type::string(),
					'description' => 'Поле для сортировки'
				],
				'ascending' => [
					'type' => Type::boolean(),
					'description' => 'True (default) for ascending sorting, false for descending'
				]
			]
		]);
	}
}