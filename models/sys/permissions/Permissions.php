<?php
declare(strict_types = 1);

namespace app\models\sys\permissions;

use app\models\sys\permissions\active_record\Permissions as ActiveRecordPermissions;

/**
 * Class Permissions
 */
class Permissions extends ActiveRecordPermissions {
	/*Любое из перечисленных прав*/
	public const LOGIC_OR = 0;
	/*Все перечисленные права*/
	public const LOGIC_AND = 1;
	/*Ни одно из перечисленных прав*/
	public const LOGIC_NOT = 2;

	/*Минимальный/максимальный приоритет*/
	public const PRIORITY_MIN = 0;
	public const PRIORITY_MAX = 100;

	/**
	 * @param int $user_id
	 * @param string[] $permissionFilters
	 * @return self[]
	 */
	public static function allUserPermissions(int $user_id, array $permissionFilters = []):array {
		$query = self::find()
			->distinct()
			->joinWith(['relatedUsersToPermissions directPermissions', 'relatedUsersToPermissionsCollections collectionPermissions'], false)
			->where(['directPermissions.user_id' => $user_id])
			->orWhere(['collectionPermissions.user_id' => $user_id])
			->orderBy([
				'priority' => SORT_DESC,
				'id' => SORT_ASC]);
		foreach ($permissionFilters as $paramName => $paramValue) {
			$query->andFilterWhere([self::tableName().".".$paramName => $paramValue]);
		}
		return $query->all();
	}
}