<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\users\inputs;

use app\models\sys\users\Users;
use app\modules\graphql\components\BaseInputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class SellerFilterInput
 */
class UsersFilterInput extends BaseInputObjectType {

	/**
	 * @inheritDoc
	 * todo
	 */
	public function __construct() {
		$user = new Users();
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => $user->getAttributeLabel('id'),
				],
				'username' => [
					'type' => Type::string(),
					'description' => $user->getAttributeLabel('username'),
				],
				'login' => [
					'type' => Type::string(),
					'description' => 'Логин',
				],
				'email' => [
					'type' => Type::string(),
					'description' => 'Электронная почта',
				],
			]
		]);
	}
}