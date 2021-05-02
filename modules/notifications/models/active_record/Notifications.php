<?php
declare(strict_types = 1);

namespace app\modules\notifications\models\active_record;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_notifications".
 *
 * @property int $id
 * @property int|null $user
 * @property string|null $type Notification handler
 * @property string|null $data Notification data
 * @property string|null $create_date
 * @property string|null $sent_date
 * @property string|null $delegate
 */
class Notifications extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'sys_notifications';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user'], 'integer'],
			[['data'], 'string'],
			[['create_date', 'sent_date'], 'safe'],
			[['type', 'delegate'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'user' => 'Пользователь',
			'type' => 'Обработчик',
			'data' => 'Сообщение',
			'create_date' => 'Создано',
			'sent_date' => 'Отправлено',
			'delegate' => 'Delegate',
		];
	}
}
