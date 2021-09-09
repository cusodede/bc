<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\users\fields;

use app\modules\graphql\schema\mutation\users\inputs\UsersProfileInput;
use GraphQL\Type\Definition\EnumType;

/**
 * Enum на атрибуты пользователя.
 */
class UserFromField extends EnumType
{
	public function __construct()
	{
		parent::__construct([
			'name' => 'FormUsersField',
			'values' => array_keys((new UsersProfileInput('Create'))->getFields()),
		]);
	}
}