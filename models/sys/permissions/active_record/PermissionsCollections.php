<?php
declare(strict_types = 1);

namespace app\models\sys\permissions\active_record;

use app\models\sys\permissions\active_record\relations\RelPermissionsCollectionsToPermissions;
use pozitronik\core\traits\ARExtended;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_permissions_collections".
 *
 * @property int $id
 * @property string|null $name Название группы доступа
 * @property string|null $comment Описание группы доступа
 *
 * @property RelPermissionsCollectionsToPermissions[] $relatedPermissionsCollectionsToPermissions Связь к промежуточной таблице к правам доступа
 * @property Permissions[] $relatedPermissions Входящие в группу доступа права доступа
 * @property-read Permissions[] $unrelatedPermissions Права доступа, которые не включены в набор
 */
class PermissionsCollections extends ActiveRecord {
	use ARExtended;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_permissions_collections';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['comment'], 'string'],
			[['name'], 'string', 'max' => 128],
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
			'comment' => 'Comment',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedPermissionsCollectionsToPermissions():ActiveQuery {
		return $this->hasMany(RelPermissionsCollectionsToPermissions::class, ['collection_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedPermissions():ActiveQuery {
		return $this->hasMany(Permissions::class, ['id' => 'permission_id'])->via('relatedPermissionsCollectionsToPermissions');
	}

	/**
	 * @param array $relatedPermissions
	 * @throws Throwable
	 */
	public function setRelatedPermissions(array $relatedPermissions):void {
		RelPermissionsCollectionsToPermissions::linkModels($this, $relatedPermissions);
	}

	/**
	 * @return Permissions[]
	 */
	public function getUnrelatedPermissions():array {
		return Permissions::find()->where(['not in', 'id', ArrayHelper::getColumn($this->relatedPermissions, 'id')])->all();
	}


}
