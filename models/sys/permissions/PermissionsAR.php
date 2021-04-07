<?php
declare(strict_types = 1);

namespace app\models\sys\permissions;

use app\models\sys\permissions\relations\RelPermissionsCollectionsToPermissions;
use app\models\sys\permissions\relations\RelUsersToPermissions;
use app\models\sys\permissions\relations\RelUsersToPermissionsCollections;
use pozitronik\core\traits\ARExtended;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_permissions".
 *
 * @property int $id
 * @property string|null $name Название доступа
 * @property string|null $controller Контроллер, к которому устанавливается доступ, null для внутреннего доступа
 * @property string|null $action Действие, для которого устанавливается доступ, null для всех действий контроллера
 * @property string|null $verb REST-метод, для которого устанавливается доступ
 * @property string|null $comment Описание доступа
 * @property int $priority Приоритет использования (больше - выше)
 *
 * @property RelUsersToPermissions[] $relatedUsersToPermissions Связь к промежуточной таблице к правам доступа
 * @property RelUsersToPermissionsCollections[] $relatedUsersToPermissionsCollections Связь к таблице к группам прав доступа через промежуточную таблицу
 * @property RelPermissionsCollectionsToPermissions[] $relatedPermissionsCollectionsToPermissions Связь к промежуточной таблице прав доступа из групп прав доступа
 */
abstract class PermissionsAR extends ActiveRecord {
	use ARExtended;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_permissions';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['comment'], 'string'],
			[['priority'], 'integer'],
			[['name'], 'string', 'max' => 128],
			[['controller', 'action', 'verb'], 'string', 'max' => 255],
			[['name'], 'unique'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Name',
			'controller' => 'Controller',
			'action' => 'Action',
			'verb' => 'Verb',
			'comment' => 'Comment',
			'priority' => 'Priority',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUsersToPermissions():ActiveQuery {
		return $this->hasMany(RelUsersToPermissions::class, ['permission_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedPermissionsCollectionsToPermissions():ActiveQuery {
		return $this->hasMany(RelPermissionsCollectionsToPermissions::class, ['permission_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUsersToPermissionsCollections():ActiveQuery {
		return $this->hasMany(RelUsersToPermissionsCollections::class, ['collection_id' => 'collection_id'])->via('relatedPermissionsCollectionsToPermissions');
	}

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
