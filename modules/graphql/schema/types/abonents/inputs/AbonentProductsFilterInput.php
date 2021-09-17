<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\abonents\inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Фильтра для абонента.
 */
class AbonentProductsFilterInput extends InputObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'abonent_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор абонента',
				],
			]
		]);
	}
}