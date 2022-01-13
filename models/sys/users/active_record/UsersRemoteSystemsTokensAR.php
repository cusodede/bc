<?php
declare(strict_types = 1);

namespace app\models\sys\users\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\sys\users\Users;
use app\modules\history\behaviors\HistoryBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sys_users_remote_systems_tokens".
 *
 * @property int $id
 * @property int $user_id
 * @property int $remote_system_id
 * @property int $token_type_id
 * @property string $token_value
 * @property int $seller_id
 * @property string $expired_at
 * @property string $created_at
 *
 * @property Users $user
 */
class UsersRemoteSystemsTokensAR extends ActiveRecord {
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'sys_users_remote_systems_tokens';
	}

	/**
	 * {@inheritdoc}
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
	public function rules():array {
		return [
			[['user_id', 'remote_system_id', 'token_type_id', 'seller_id'], 'integer'],
			[['expired_at', 'created_at'], 'safe'],
			[['token_value'], 'safe'],
			[['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'remote_system_id' => 'ID внешней системы',
			'token_type_id' => 'Тип токена (access, refresh, etc)',
			'token_value' => 'Значение токена',
			'seller_id' => 'Id продавца в ДОЛ',
			'expired_at' => 'Срок действия',
			'created_at' => 'Дата создания токена',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getUser():ActiveQuery {
		return $this->hasOne(Users::class, ['id' => 'user_id']);
	}
}
