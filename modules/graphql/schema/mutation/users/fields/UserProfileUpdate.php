<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\users\fields;

use app\modules\graphql\components\AuthHelper;
use app\modules\graphql\components\BaseMutationType;
use app\modules\graphql\schema\mutation\users\inputs\UsersProfileInput;
use app\modules\graphql\schema\types\common\ResponseType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Class UserProfileUpdate
 * @package app\modules\graphql\schema\mutation\users\fields
 */
class UserProfileUpdate extends BaseMutationType
{
	public const MESSAGES = ['Ошибка сохранения профиля', 'Профиль успешно сохранён'];

	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'update',
			'description' => 'Обновление профиля текущего пользователя',
			'type' => ResponseType::type(),
			'args' => [
				'data' => [
					'type' => Type::nonNull(new UsersProfileInput('Update')),
				]
			],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): array
	{
		$user = AuthHelper::authenticate();
		$data = ArrayHelper::getValue($args, 'data', []);
		// Фронт не готов отправлять массив номеров.
		$data['phones'] = ArrayHelper::merge($user->phones, [ArrayHelper::getValue($data, 'phones', [])]);
		return static::save(AuthHelper::authenticate(), $data, self::MESSAGES);
	}
}