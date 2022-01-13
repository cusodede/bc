<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\users\fields;

use app\models\sys\users\UsersSearch;
use app\modules\graphql\components\BasePaginatedField;
use app\modules\graphql\components\ResolveParameter;
use app\modules\graphql\schema\types\users\inputs\UsersFilterInput;
use app\modules\graphql\schema\types\users\UserType;
use GraphQL\Type\Definition\Type;

/**
 * Class UsersListField
 */
class UsersListField extends BasePaginatedField {

	/**
	 * @inheritDoc
	 */
	protected function __construct(bool $paginated = false) {
		parent::__construct([
			'name' => 'usersList',
			'type' => Type::listOf(UserType::type()),
			'description' => 'Список пользователей',
			'args' => [
				'filters' => [
					'type' => UsersFilterInput::type(),
				]
			],
		], $paginated);
	}

	/**
	 * @inheritDoc
	 */
	public static function resolve(ResolveParameter $resolveParameter):array {
		$searchModel = new UsersSearch();
		$resolveParameter->dataProvider = $searchModel->search($resolveParameter->searchData($searchModel->formName()));
		return $resolveParameter();//magic
	}
}
