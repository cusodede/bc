<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\abonents\inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Фильтрация по абоненту.
 */
class AbonentsFilterInput extends InputObjectType
{
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор абонента',
				],
				'phone' => [
					'type' => Type::string(),
					'description' => 'Поиск по телефону'
				],
			]
		]);
	}
}