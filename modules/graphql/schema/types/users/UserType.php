<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\users;

use app\models\sys\users\Users;
use app\modules\graphql\components\BaseObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class SellerType
 * @package app\modules\graphql\schema\types
 */
class UserType extends BaseObjectType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		$user = new Users();
		parent::__construct([
			'description' => 'Пользователь',
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => $user->getAttributeLabel('id'),
				],
				'username' => [
					'type' => Type::string(),
					'description' => $user->getAttributeLabel('username'),
				],
				'surname' => [
					'type' => Type::string(),
					'description' => $user->getAttributeLabel('surname'),
				],
				'login' => [
					'type' => Type::string(),
					'description' => 'Логин',
				],
				'email' => [
					'type' => Type::string(),
					'description' => 'Электронная почта',
				],
			],
		]);
	}
}
