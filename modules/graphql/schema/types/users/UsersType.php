<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\users;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\types\users\fields\UserProfileField;
use app\modules\graphql\schema\types\users\fields\UserRolesField;
use app\modules\graphql\schema\types\users\fields\UsersListField;
use app\modules\graphql\schema\types\users\fields\UserSortField;

/**
 * Class SellersType
 */
class UsersType extends BaseObjectType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'description' => 'Пользователи',
			'fields' => [
				'usersList' 	=> UsersListField::field(),
				'userProfile' 	=> UserProfileField::field(),
				'userRoles' 	=> new UserRolesField(),
				'userSort' 		=> new UserSortField(),
			]
		]);
	}
}