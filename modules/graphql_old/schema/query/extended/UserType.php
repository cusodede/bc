<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query\extended;

use app\models\sys\users\Users;
use app\models\sys\users\UsersSearch;
use app\modules\graphql\base\BaseQueryType;
use app\modules\graphql\data\QueryTypes;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Class UserType
 * @package app\modules\graphql\schema\query\extended
 */
final class UserType extends BaseQueryType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор партнера',
				],
				'username' => [
					'type' => Type::string(),
					'description' => 'ФИО пользователя'
				],
				'login' => [
					'type' => Type::string(),
					'description' => 'Логин'
				],
				'email' => [
					'type' => Type::string(),
					'description' => 'Почтовый адрес пользователя'
				],
				'comment' => [
					'type' => Type::string(),
					'description' => 'Комментарий'
				],
				'deleted' => [
					'type' => Type::boolean(),
					'description' => 'Флаг удаления'
				],
			],
		]);
	}

	/**
	 * Список пользователей.
	 * @return array
	 */
	public static function getListOfType(): array
	{
		return [
			'type' => Type::listOf(QueryTypes::user()),
			'description' => 'Возвращаем список пользователей',
			'resolve' => function(Users $user = null, array $args = []): array {
				$userSearch = new UsersSearch();
				ArrayHelper::setValue($args, 'pagination', false);
				return $userSearch->search([$userSearch->formName() => $args])->getModels();
			},
		];
	}

	/**
	 * Конкретный пользователь.
	 * @return array
	 */
	public static function getOneOfType(): array
	{
		return [
			'type' => QueryTypes::user(),
			'args' => [
				'id' => Type::int(),
			],
			'description' => 'Возвращает текущего пользователя если обратиться без id',
			'resolve' => fn(Users $user = null, array $args = []): ?Users
				=> null === ($id = ArrayHelper::getValue($args, 'id')) ? Users::Current() : Users::findOne($id),
		];
	}
}