<?php
declare(strict_types = 1);

namespace app\models\prototypes\merch\active_record\relations;

use pozitronik\core\traits\Relations;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "relation_order_to_merch".
 *
 * Связь заказов с товарами
 * @property int $id
 * @property int $order_id
 * @property int $merch_id
 */
class RelMerchOrderToMerch extends ActiveRecord {
	use Relations;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'relation_order_to_merch';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['order_id', 'merch_id'], 'required'],
			[['order_id', 'merch_id'], 'integer'],
			[['order_id', 'merch_id'], 'unique', 'targetAttribute' => ['order_id', 'merch_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'order_id' => 'Order ID',
			'merch_id' => 'Merch ID',
		];
	}
}
