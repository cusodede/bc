<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query\extended\enum;

use GraphQL\Type\Definition\EnumType;

/**
 * Class FormSubscriptionsType
 * @package app\modules\graphql\schema\query\extended\enum
 */
class FormSubscriptionsType extends EnumType
{
	/**
	 * FormSubscriptionsType constructor.
	 * @param array $fields
	 */
	public function __construct(array $fields)
	{
		parent::__construct([
			'name' => 'FormSubscriptionsField',
			'values' => array_keys($fields),
		]);
	}
}