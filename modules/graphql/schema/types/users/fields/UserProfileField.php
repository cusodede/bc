<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\users\fields;

use app\modules\graphql\components\AuthHelper;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\components\ResolveParameter;
use app\modules\graphql\schema\types\users\UserType;

/**
 * Class UserProfileField
 */
class UserProfileField extends BaseField {

	/**
	 * @inheritDoc
	 */
	protected function __construct() {
		parent::__construct([
			'name' => 'userProfile',
			'description' => 'Профиль пользователя',
			'type' => UserType::type(),

		]);
	}

	/**
	 * @inheritDoc
	 */
	public static function resolve(ResolveParameter $resolveParameter):array {
		$user = AuthHelper::authenticate();

		return [
			'id' => $user->id,
			'username' => $user->username,
			'login' => $user->login,
			'email' => $user->email
		];
	}
}