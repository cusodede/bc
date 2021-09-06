<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\partners\inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class PartnersCategoriesFilterInput
 * @package app\modules\graphql\schema\types\partners\inputs
 */
class PartnersCategoriesFilterInput extends InputObjectType
{
	/**
	 * PartnersCategoriesFilterInput constructor.
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор категории',
				]
			]
		]);
	}
}