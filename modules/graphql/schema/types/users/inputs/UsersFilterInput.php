<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\users\inputs;

use app\models\sys\users\Users;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class SellerFilterInput
 */
class UsersFilterInput extends InputObjectType
{
	/**
	 * UsersFilterInput constructor.
	 */
	public function __construct()
	{
		$user = new Users();
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => $user->getAttributeLabel('id'),
				],
				'name' => [
					'type' => Type::string(),
					'description' => $user->getAttributeLabel('name'),
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
				'search' => [
					'type' => Type::string(),
					'description' => 'Поиск по имени, фамилии, почте, минимум 3 символа'
				],
			]
		]);
	}
}