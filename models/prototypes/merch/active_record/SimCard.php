<?php
declare(strict_types = 1);

namespace app\models\prototypes\merch\active_record;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "m_simcard".
 *
 * @property int $id
 * @property int $ICCID
 * @property bool $active
 */
class SimCard extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'm_simcard';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['ICCID'], 'required'],
			[['ICCID'], 'integer'],
			[['active'], 'boolean']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'ICCID' => 'Iccid',
			'active' => 'Active',
		];
	}
}
