<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\users\fields;

use app\models\sys\users\Users;
use app\modules\graphql\components\BaseMutationType;
use app\modules\graphql\schema\mutation\users\inputs\UsersProfileInput;
use app\modules\graphql\schema\types\common\ResponseType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Class UserProfileCreate
 * @package app\modules\graphql\schema\mutation\users\fields
 */
class UserProfileCreate extends BaseMutationType
{

	public const MESSAGES = ['Ошибка добавления профиля', 'Профиль успешно добавлен'];

	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'create',
			'description' => 'Добавление нового пользователя',
			'type' => ResponseType::type(),
			'args' => [
				'data' => [
					'type' => Type::nonNull(new UsersProfileInput('Create')),
				]
			],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): array
	{
		return static::save(new Users(), ArrayHelper::getValue($args, 'data', []), self::MESSAGES);
	}
}