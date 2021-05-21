<?php
declare(strict_types = 1);

namespace app\models\reward\active_record;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\reward\active_record\references\RefRewardOperations;
use app\models\reward\active_record\references\RefRewardRules;
use app\models\reward\active_record\references\RefRewardStatuses;
use app\models\sys\users\Users;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "reward".
 *
 * @property int $id
 * @property int $status Статус
 * @property int $user Аккаунт
 * @property int $operation Операция
 * @property int $rule Правило расчёта
 * @property int $value Расчётное вознаграждение
 * @property string $comment Произвольный комментарий
 * @property string $create_date Дата создания
 * @property int $override Переопределено
 * @property int $deleted Флаг удаления
 *
 * @property RefRewardStatuses $refRewardStatus Справочник статусов
 * @property RefRewardOperations $refRewardOperation Справочник операций
 * @property RefRewardRules $refRewardRule Справочник правил расчета вознаграждения
 * @property Users $relatedUser Пользователь к которому относится вознаграждение
 */
class RewardsAR extends ActiveRecord {
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'reward';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['status', 'user', 'operation', 'rule', 'create_date'], 'required'],
			[['status', 'user', 'operation', 'rule', 'value', 'override', 'deleted'], 'integer'],
			[['comment'], 'string'],
			[['create_date'], 'safe'],
			[['override'], 'unique'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'status' => 'Статус',
			'user' => 'Аккаунт',
			'operation' => 'Операция',
			'rule' => 'Правило расчёта',
			'value' => 'Расчётное вознаграждение',
			'comment' => 'Произвольный комментарий',
			'create_date' => 'Дата создания',
			'override' => 'Переопределено',
			'deleted' => 'Флаг удаления',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRefRewardStatus():ActiveQuery {
		return $this->hasOne(RefRewardStatuses::class, ['id' => 'status']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRefRewardOperation():ActiveQuery {
		return $this->hasOne(RefRewardOperations::class, ['id' => 'operation']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRefRewardRule():ActiveQuery {
		return $this->hasOne(RefRewardRules::class, ['id' => 'rule']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUser():ActiveQuery {
		return $this->hasOne(Users::class, ['id' => 'user']);
	}
}
