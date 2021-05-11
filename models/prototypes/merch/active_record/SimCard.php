<?php
declare(strict_types = 1);

namespace app\models\prototypes\merch\active_record;

use app\models\core\prototypes\ActiveRecordTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "m_simcard".
 *
 * @property int $id
 * @property int $ICCID
 * @property bool $active
 */
class SimCard extends ActiveRecord {
	use ActiveRecordTrait;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'm_simcard';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['ICCID'], 'required'],
			[['ICCID'], 'integer'],
			[['active'], 'boolean']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'ICCID' => 'Iccid',
			'active' => 'Active',
		];
	}
}
