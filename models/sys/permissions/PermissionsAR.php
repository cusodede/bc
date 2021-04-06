<?php
declare(strict_types = 1);

namespace app\models\sys\permissions;

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
 */
class PermissionsAR extends ActiveRecord {
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
}
