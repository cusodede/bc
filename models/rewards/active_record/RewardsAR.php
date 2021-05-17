<?php
declare(strict_types = 1);

namespace app\models\rewards\active_record;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "rewards".
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
 */
class RewardsAR extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'rewards';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
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
	public function attributeLabels() {
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
}
