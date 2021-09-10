<?php
declare(strict_types=1);

namespace app\modules\graphql\schema\types\users\fields;

use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\users\inputs\UsersFilterInput;
use app\modules\graphql\schema\types\users\UserType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use app\models\sys\users\UsersSearch;
use yii\helpers\ArrayHelper;

/**
 * Class SellersListField
 */
class UsersListField extends BaseField
{
	/**
	 * @inheritDoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'usersList',
			'type' => Type::listOf(UserType::type()),
			'description' => 'Список пользователей',
			'args' => [
				'filters' => [
					'type' => new UsersFilterInput(),
				],
				'sort' => [
					'type' => new UserSortField('List'),
					'description' => 'Сортировка: surname, -surname, created_at, -created_at',
				],
				'limit' => Type::int(),
				'offset' => Type::int()
			],
		]);
	}

	/**
	 * @inheritDoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ResolveInfo $resolveInfo = null): array
	{
		$userSearch = new UsersSearch();
		$filters = ArrayHelper::getValue($args, 'filters', []);
		ArrayHelper::setValue($args, 'pagination', false);
		return $userSearch->search([$userSearch->formName() => ArrayHelper::merge($args, $filters)])->getModels();
	}
}