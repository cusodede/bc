<?php
declare(strict_types = 1);

namespace app\models\sys\permissions;

use app\models\core\CacheHelper;
use app\models\sys\permissions\active_record\relations\RelUsersToPermissions;
use app\models\sys\permissions\active_record\relations\RelUsersToPermissionsCollections;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Permissions
 * Управление правами доступа
 * @property int $id Model primary key attribute name
 *
 * @property RelUsersToPermissions[] $relatedUsersToPermissions Связь к промежуточной таблице пользовательских доступов
 * @property RelUsersToPermissionsCollections[] $relatedUsersToPermissionsCollections Связь к промежуточной таблице наборов пользовательских доступов
 * @property Permissions[] $relatedPermissions Назначенные напрямую доступы
 * @property PermissionsCollections[] $relatedPermissionsCollections Назначенные напрямую доступы
 */
trait UsersPermissionsTrait {
	/**
	 * Проверяет, имеет ли пользователь указанный набор прав с указанной логикой проверки.
	 * Примеры:
	 * Permissions::has(['execute_order_66'])
	 * Permissions::has(['rule_galaxy', 'lose_arm'], self::LOGIC_AND)
	 *
	 * @param string[] $permissions Названия прав, к которым проверяются доступы
	 * @param int $logic Логика проверки
	 * @return bool
	 * @throws Throwable
	 */
	public function hasPermission(array $permissions, int $logic = Permissions::LOGIC_OR):bool {
		$cacheKey = CacheHelper::MethodSignature(__METHOD__, func_get_args(), ['id' => $this->id]);
		return Yii::$app->cache->getOrSet($cacheKey, function() use ($permissions, $logic) {
			$result = false;
			$allUserPermissionsNames = ArrayHelper::getColumn(self::allPermissions(), 'name');
			foreach ($permissions as $current_permission_name) {
				$result = in_array(trim($current_permission_name), $allUserPermissionsNames, true);

				switch ($logic) {
					case Permissions::LOGIC_OR:
						if ($result) return true; //при первом же найденном совпадении рапортуем о удаче
					break;
					case Permissions::LOGIC_AND:
						if (!$result) return false;//при первом же не найденном совпадении рапортуем о неудаче
					break;
					case Permissions::LOGIC_NOT:
						if ($result) return false;//при первом же найденном совпадении рапортуем о неудаче
					break;
				}
			}
			return ($logic === Permissions::LOGIC_NOT)?true:$result;
		}, null, new TagDependency(['tags' => CacheHelper::MethodSignature('Users::hasPermission', ['id' => $this->id])]));//тег ставится на все варианты запроса ролей пользователя для сброса скопом

	}

	/**
	 * Все доступы пользователя, отсортированные по приоритету от большего к меньшему
	 * Учитываются доступы групп пользователя + прямые доступы, без разделения
	 * @param bool $force false (default): получить кешированный набор прав; true: получить актуальный набор прав с обновлением кеша
	 * @return self[]
	 * @throws Throwable
	 */
	public function allPermissions(bool $force = false):array {
		$cacheKey = CacheHelper::MethodSignature('Users::allPermissions', func_get_args(), ['id' => $this->id]);
		if ($force) Yii::$app->cache->delete($cacheKey);
		return Yii::$app->cache->getOrSet($cacheKey, function() {
			return Permissions::allUserPermissions($this->id);
		}, null, new TagDependency(['tags' => $cacheKey]));
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUsersToPermissions():ActiveQuery {
		return $this->hasMany(RelUsersToPermissions::class, ['user_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedPermissions():ActiveQuery {
		return $this->hasMany(Permissions::class, ['id' => 'permission_id'])->via('relatedUsersToPermissions');
	}

	/**
	 * @param Permissions[] $relatedPermissions
	 * @throws Throwable
	 */
	public function setRelatedPermissions($relatedPermissions):void {
		/** @var ActiveRecord $this */
		if (empty($relatedPermissions)) {
			RelUsersToPermissions::clearLinks($this);
		} else {
			RelUsersToPermissions::linkModels($this, $relatedPermissions);
		}
		TagDependency::invalidate(Yii::$app->cache, [CacheHelper::MethodSignature('Users::allPermissions', ['id' => $this->id])]);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUsersToPermissionsCollections():ActiveQuery {
		return $this->hasMany(RelUsersToPermissionsCollections::class, ['user_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedPermissionsCollections():ActiveQuery {
		return $this->hasMany(PermissionsCollections::class, ['id' => 'collection_id'])->via('relatedUsersToPermissionsCollections');
	}

	/**
	 * @param PermissionsCollections[] $relatedPermissionsCollections
	 * @throws Throwable
	 */
	public function setRelatedPermissionsCollections($relatedPermissionsCollections):void {
		/** @var ActiveRecord $this */
		if (empty($relatedPermissionsCollections)) {
			RelUsersToPermissionsCollections::clearLinks($this);
		} else {
			RelUsersToPermissionsCollections::linkModels($this, $relatedPermissionsCollections);
		}
		TagDependency::invalidate(Yii::$app->cache, [CacheHelper::MethodSignature('Users::allPermissions', ['id' => $this->id])]);
	}

}