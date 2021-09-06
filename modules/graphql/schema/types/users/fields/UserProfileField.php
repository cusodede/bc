<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\users\fields;

use app\models\sys\users\Users;
use app\modules\graphql\components\AuthHelper;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\users\UserType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Class UserProfileField
 */
class UserProfileField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'userProfile',
			'args' => [
				'id' => Type::int(),
			],
			'description' => 'Возвращает текущего пользователя, а если указать id, вернёт конкретного пользователя.',
			'type' => UserType::type(),
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): Users
	{
		return null === ($id = ArrayHelper::getValue($args, 'id')) ? AuthHelper::authenticate() : Users::findOne($id);
	}
}