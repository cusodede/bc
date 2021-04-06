<?php
declare(strict_types = 1);

namespace app\models\sys\permissions\relations;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_relation_users_to_permissions".
 *
 * @property int $id
 * @property int $user_id Ключ объекта доступа
 * @property int $permission_id Ключ правила доступа
 */
class RelUsersToPermissions extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_relation_users_to_permissions';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user_id', 'permission_id'], 'required'],
			[['user_id', 'permission_id'], 'integer'],
			[['user_id', 'permission_id'], 'unique', 'targetAttribute' => ['user_id', 'permission_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'permission_id' => 'Permission ID',
		];
	}
}
