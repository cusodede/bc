<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\users\fields;

use app\models\sys\users\EnumUsersRoles;
use GraphQL\Type\Definition\EnumType;

/**
 * Class UserRolesField
 * @package app\modules\graphql\schema\types\users\fields
 */
class UserRolesField extends EnumType
{
	public function __construct()
	{
		parent::__construct([
			'name' => 'UserRolesField',
			'values' => array_keys(EnumUsersRoles::mapData())
		]);
	}
}