<?php
declare(strict_types = 1);

namespace app\models\sys\permissions\traits;

use app\models\sys\users\Users;
use yii\db\ActiveQueryInterface;
use yii\web\IdentityInterface;
use Throwable;

/**
 * Trait ActiveRecordPermissionsTrait
 * Управление областями видимости в ActiveRecord
 */
trait ActiveRecordPermissionsTrait {

	/**
	 * Интерфейс функции установки области доступа пользователя в этой таблице
	 * @param ActiveQueryInterface $query
	 * @param IdentityInterface $user
	 * @return mixed
	 * @throws Throwable
	 */
	public static function scope(ActiveQueryInterface $query, IdentityInterface $user):ActiveQueryInterface {
		/** @var Users $user */
		return ($user->isAllPermissionsGranted())
			?$query
			:$query->where([self::tableName().'.id' => '0']);
	}

}