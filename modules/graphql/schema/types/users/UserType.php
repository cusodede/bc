<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\users;

use app\models\partners\Partners;
use app\models\sys\users\Users;
use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\types\partners\PartnerType;
use app\services\permissions\UserPermissionsService;
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
					'description' => $user->getAttributeLabel('login'),
				],
				'partner_id' => [
					'type' => Type::int(),
					'description' => $user->getAttributeLabel('partner_id'),
				],
				'partner' => [
					'type' => PartnerType::type(),
					'resolve' => fn(Users $user): ?Partners => $user->relatedPartner,
				],
				'email' => [
					'type' => Type::string(),
					'description' => 'Электронная почта',
				],
				'role' => [
					'type' => Type::string(),
					'description' => 'Роль пользователя',
					'resolve' => fn(Users $user): string => (new UserPermissionsService($user))->getMainPermission() ?? '',
				],
				'phones' => [
					'type' => Type::string(),
					'description' => 'Телефон',
					'resolve' => function(Users $user): ?string {
						// Фронт не готов принимать тут массив и хочет номера без плюсов
						$phones = $user->getPhones();
						$phone =  array_pop($phones);
						return null === $phone ? '' : str_replace('+', '',$phone);
					},
				],
				'deleted' => Type::boolean()
			],
		]);
	}
}
