<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query\extended\enum;

use GraphQL\Type\Definition\EnumType;

/**
 * Class FormPartnersType
 * @package app\modules\graphql\schema\query\extended\enum
 */
class FormPartnersType extends EnumType
{
	/**
	 * FormPartnersType constructor.
	 * @param array $fields
	 */
	public function __construct(array $fields)
	{
		parent::__construct([
			'name' => 'FormPartnersField',
			'values' => array_keys($fields),
		]);
	}
}
