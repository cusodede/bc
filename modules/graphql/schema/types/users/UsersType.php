<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\users;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\types\users\fields\UserProfileField;
use app\modules\graphql\schema\types\users\fields\UsersListField;

/**
 * Class SellersType
 */
class UsersType extends BaseObjectType {

	/**
	 * {@inheritdoc}
	 */
	public function __construct() {
		parent::__construct([
			'description' => 'Пользователи',
			'fields' => [
				'usersList' => UsersListField::field(),
				'usersListPaginated' => UsersListField::field(true),
				'userProfile' => UserProfileField::field()
			]
		]);
	}
}