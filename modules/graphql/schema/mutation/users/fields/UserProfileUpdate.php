<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\users\fields;

use app\models\sys\users\Users;
use app\modules\graphql\components\AuthHelper;
use app\modules\graphql\components\BaseMutationType;
use app\modules\graphql\schema\mutation\users\inputs\UsersProfileInput;
use app\modules\graphql\schema\types\common\ResponseType;
use app\services\permissions\UserPermissionsService;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\db\Exception;
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
				'id' => Type::int(),
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
		$user = (null === ($id = ArrayHelper::getValue($args, 'id')) ? AuthHelper::authenticate() : Users::findOne($id));

		if (null === $user) {
			throw new Exception("Не найдена модель для обновления.");
		}

		$data = ArrayHelper::getValue($args, 'data', []);
		$user->phones = ArrayHelper::merge($user->phones, [ArrayHelper::getValue($data, 'phones', [])]); // Фронт не готов отправлять массив номеров.
		$user->relatedPermissions = (new UserPermissionsService($user))->resetMainPermission(ArrayHelper::getValue($data, 'role'));
		return static::save($user, $data, self::MESSAGES);
	}
}