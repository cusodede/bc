<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\users;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\mutation\users\fields\UserProfileUpdate;

/**
 * Class UsersMutationType
 * @package app\modules\graphql\schema\mutation\users
 */
class UsersMutationType extends BaseObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct([
			'description' => 'Мутации пользователей',
			'fields' => [
				'updateProfile' => UserProfileUpdate::field(),
			]
		]);
	}
}