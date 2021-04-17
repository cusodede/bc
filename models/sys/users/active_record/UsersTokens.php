<?php
declare(strict_types = 1);

namespace app\models\sys\users\active_record;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_users_tokens".
 *
 * @property int $id
 * @property int $user_id user id foreign key
 * @property string $auth_token Bearer auth token
 * @property string $created Таймстамп создания
 * @property string|null $valid Действует до
 * @property string|null $ip Адрес авторизации
 * @property string|null $user_agent User-Agent
 *
 * @property null|Users $relatedUsers
 */
class UsersTokens extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'sys_users_tokens';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['user_id', 'auth_token'], 'required'],
			[['user_id'], 'integer'],
			[['created', 'valid'], 'safe'],
			[['auth_token'], 'string', 'max' => 40],
			[['ip', 'user_agent'], 'string', 'max' => 255],
			[['user_id', 'auth_token'], 'unique', 'targetAttribute' => ['user_id', 'auth_token']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'user_id' => 'user id foreign key',
			'auth_token' => 'Bearer auth token',
			'created' => 'Время создания',
			'valid' => 'Действует до',
			'ip' => 'Адрес авторизации',
			'user_agent' => 'User-Agent',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUsers():ActiveQuery {
		return $this->hasOne(Users::class, ['id' => 'user_id']);
	}
}
