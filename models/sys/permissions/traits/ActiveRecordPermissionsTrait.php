<?php
declare(strict_types = 1);

namespace app\models\sys\permissions\traits;

use app\models\sys\users\Users;
use Throwable;
use yii\db\ActiveQueryInterface;
use yii\web\IdentityInterface;

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
	 * @see ActiveQueryPermissionsTrait::scope()
	 */
	public static function scope(ActiveQueryInterface $query, IdentityInterface $user):ActiveQueryInterface {
		/** @var Users $user */
		return ($user->isAllPermissionsGranted())
			?$query
			:$query->where([self::tableName().'.id' => '0']);
	}

}