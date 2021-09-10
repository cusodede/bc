<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\users\inputs;

use app\modules\graphql\schema\types\users\fields\UserRolesField;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class UsersProfileInput
 * @package app\modules\graphql\schema\mutation\users\inputs
 */
class UsersProfileInput extends InputObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct(string $rootName)
	{
		parent::__construct([
			'name' => $rootName . 'UserProfileData',
			'fields' => [
				'name' => [
					'type' => Type::string(),
					'description' => 'Имя',
				],
				'surname' => [
					'type' => Type::string(),
					'description' => 'Фамилия',
				],
				'email' => [
					'type' => Type::string(),
					'description' => 'Email',
				],
				'phones' => [
					'type' => Type::string(),
					'description' => 'Телефон в формате +79999999999',
				],
				'partner_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор партнёра',
				],
				'role' => [
					'type' => new UserRolesField($rootName),
					'description' => 'Роль пользователя',
				],
			]
		]);
	}
}