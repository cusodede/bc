<?php
declare(strict_types = 1);

namespace app\models\sys\permissions;


use app\models\sys\permissions\active_record\Permissions as ActiveRecordPermissions;

/**
 * Class Permissions
 */
final class Permissions extends ActiveRecordPermissions {
	/*Любое из перечисленных прав*/
	public const LOGIC_OR = 0;
	/*Все перечисленные права*/
	public const LOGIC_AND = 1;
	/*Ни одно из перечисленных прав*/
	public const LOGIC_NOT = 2;

	/**
	 * @param int $user_id
	 * @return self[]
	 */
	public static function allUserPermissions(int $user_id):array {
		return self::find()
			->distinct()
			->joinWith(['relatedUsersToPermissions directPermissions', 'relatedUsersToPermissionsCollections collectionPermissions'], false)
			->where(['directPermissions.user_id' => $user_id])
			->orWhere(['collectionPermissions.user_id' => $user_id])
			->orderBy([
				'priority' => SORT_DESC,
				'id' => SORT_ASC])
			->all();
	}
}