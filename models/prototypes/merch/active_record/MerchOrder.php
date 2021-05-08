<?php
declare(strict_types = 1);

namespace app\models\prototypes\merch\active_record;

use app\models\prototypes\merch\active_record\relations\RelMerchOrderToMerch;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "merch_order".
 *
 * @property int $id
 * @property int $initiator Заказчик
 * @property int $store Магазин
 * @property string $create_date Дата регистрации
 * @property int $deleted
 *
 * @property RelMerchOrderToMerch[] $relatedMerchOrderToMerch Связь к промежуточной таблице к товарам заказа
 * @property Merch[] $merch Товары в заказе
 */
class MerchOrder extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'merch_order';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['initiator', 'store', 'create_date'], 'required'],
			[['initiator', 'store', 'deleted'], 'integer'],
			[['create_date'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'initiator' => 'Заказчик',
			'store' => 'Магазин',
			'create_date' => 'Дата регистрации',
			'deleted' => 'Deleted',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedMerchOrderToMerch():ActiveQuery {
		return $this->hasMany(RelMerchOrderToMerch::class, ['order_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getMerch():ActiveQuery {
		return $this->hasMany(Merch::class, ['id' => 'merch_id'])->via('relatedMerchOrderToMerch');
	}
}
