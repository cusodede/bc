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
	public function __construct()
	{
		parent::__construct([
			'name' => 'FormPartners',
			'values' => [
				'name',
				'inn',
				'phone',
				'email',
				'comment',
				'category_id',
				'logo',
			],
		]);
	}
}
