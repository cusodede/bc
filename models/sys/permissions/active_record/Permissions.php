<?php
declare(strict_types = 1);

namespace app\models\sys\permissions\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\sys\permissions\active_record\relations\RelPermissionsCollectionsToPermissions;
use app\models\sys\permissions\active_record\relations\RelUsersToPermissions;
use app\models\sys\permissions\active_record\relations\RelUsersToPermissionsCollections;
use app\models\sys\permissions\Permissions as ParentPermissions;
use app\models\sys\users\Users;
use app\modules\history\behaviors\HistoryBehavior;
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
 * @property string|null $module Модуль, к которому устанавливается доступ, null, если проверяется контроллер приложения. Проверяется только вместе с контроллером.
 * @property string|null $comment Описание доступа
 * @property int $priority Приоритет использования (больше - выше) {unused}
 *
 * @property RelUsersToPermissions[] $relatedUsersToPermissions Связь к промежуточной таблице к правам доступа
 * @property RelUsersToPermissionsCollections[] $relatedUsersToPermissionsCollections Связь к таблице к группам прав доступа через промежуточную таблицу
 * @property RelPermissionsCollectionsToPermissions[] $relatedPermissionsCollectionsToPermissions Связь к промежуточной таблице прав доступа из групп прав доступа
 * @property-read Users[] $relatedUsers Связь к пользователям, имеющим этот доступ напрямую
 * @property-read PermissionsCollections[] $relatedPermissionsCollections Связь к группам прав доступа, в которые входит доступ
 * @property-read Users[] $relatedUsersViaPermissionsCollections Связь к пользователям, имеющим этот доступ через группу доступов
 */
class Permissions extends ActiveRecord {
	use ActiveRecordTrait;

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return [
			'history' => [
				'class' => HistoryBehavior::class
			]
		];
	}

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
			[['priority'], 'default', 'value' => 0],
			[['name'], 'string', 'max' => 128],
			[['name'], 'required'],
			[['controller', 'action', 'verb', 'module'], 'string', 'max' => 255],
			[['name'], 'unique'],
			[ParentPermissions::ALLOWED_EMPTY_PARAMS, 'default', 'value' => null]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название',
			'controller' => 'Контроллер',
			'action' => 'Действие',
			'verb' => 'Метод запроса',
			'module' => 'Модуль контроллера',
			'comment' => 'Комментарий',
			'priority' => 'Приоритет',
			'relatedUsersToPermissionsCollections' => 'Входит в наборы',
			'relatedUsers' => 'Назначено пользователям'
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
	 * @return ActiveQuery
	 */
	public function getRelatedUsers():ActiveQuery {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relatedUsersToPermissions');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedPermissionsCollections():ActiveQuery {
		return $this->hasMany(PermissionsCollections::class, ['id' => 'collection_id'])->via('relatedPermissionsCollectionsToPermissions');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUsersViaPermissionsCollections():ActiveQuery {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relatedUsersToPermissionsCollections');
	}

}
