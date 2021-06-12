<?php
declare(strict_types = 1);

namespace app\models\reward\active_record;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\reward\active_record\references\RefRewardsOperations;
use app\models\reward\active_record\references\RefRewardsRules;
use app\models\sys\users\Users;
use app\modules\status\models\traits\StatusesTrait;
use pozitronik\helpers\DateHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "reward".
 *
 * @property int $id
 * @property int $user Аккаунт
 * @property int $operation Операция
 * @property int $reason Причина начисления
 * @property int $rule Правило расчёта
 * @property int $status Статус
 * @property int $quantity Расчётное вознаграждение
 * @property string|null $waiting Ожидаемое событие
 * @property string $comment Произвольный комментарий
 * @property string $create_date Дата создания
 * @property int $override Переопределено
 * @property int $deleted Флаг удаления
 *
 * @property RefRewardsOperations $refRewardsOperations Справочник операций
 * @property RefRewardsRules $refRewardsRules Справочник правил расчета вознаграждения
 * @property Users $relatedUser Пользователь к которому относится вознаграждение
 */
class RewardsAR extends ActiveRecord {
	use ActiveRecordTrait;
	use StatusesTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'rewards';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['user', 'operation', 'rule'], 'required'],
			['create_date', 'default', 'value' => DateHelper::lcDate()],
			[['user', 'operation', 'rule', 'quantity', 'override', 'deleted'], 'integer'],
			[['comment', 'waiting'], 'string'],
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
			'quantity' => 'Расчётное вознаграждение',
			'comment' => 'Произвольный комментарий',
			'create_date' => 'Дата создания',
			'override' => 'Переопределено',
			'deleted' => 'Флаг удаления'
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRefRewardsOperations():ActiveQuery {
		return $this->hasOne(RefRewardsOperations::class, ['id' => 'operation']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRefRewardsRules():ActiveQuery {
		return $this->hasOne(RefRewardsRules::class, ['id' => 'rule']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedUser():ActiveQuery {
		return $this->hasOne(Users::class, ['id' => 'user']);
	}
}
