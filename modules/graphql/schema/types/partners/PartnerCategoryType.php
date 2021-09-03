<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\partners;

use app\modules\graphql\components\BaseObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class PartnerCategoryType
 * @package app\modules\graphql\schema\types\partners
 */
class PartnerCategoryType extends BaseObjectType
{
	public function __construct()
	{
		parent::__construct([
			'description' => 'Категории партнёров',
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор категории',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование категории',
				],
			],
		]);
	}
}