<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\types\partners\PartnersType;
use app\modules\graphql\schema\types\services\ServicesType;
use app\modules\graphql\schema\types\users\UsersType;

/**
 * Class QueryType
 * @package app\schema
 */
class QueryType extends BaseObjectType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		$schema = [
			'services' 	=> ServicesType::root(),
			'users' 	=> UsersType::root(),
			'partners' 	=> PartnersType::root(),
		];

		ksort($schema, SORT_REGULAR);

		parent::__construct([
			'fields' => $schema,
		]);
	}
}
