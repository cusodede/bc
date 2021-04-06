<?php
declare(strict_types = 1);

namespace app\models\sys\permissions\relations;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_relation_permissions_collections_to_permissions".
 *
 * @property int $id
 * @property int $collection_id Ключ группы доступа
 * @property int $permission_id Ключ правила доступа
 */
class RelPermissionsCollectionsToPermissions extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_relation_permissions_collections_to_permissions';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['collection_id', 'permission_id'], 'required'],
			[['collection_id', 'permission_id'], 'integer'],
			[['collection_id', 'permission_id'], 'unique', 'targetAttribute' => ['collection_id', 'permission_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'collection_id' => 'Collection ID',
			'permission_id' => 'Permission ID',
		];
	}
}
