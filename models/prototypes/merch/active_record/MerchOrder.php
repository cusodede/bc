<?php
declare(strict_types = 1);

namespace app\models\prototypes\merch\active_record;

use app\models\prototypes\merch\active_record\relations\RelMerchOrderToMerch;
use app\models\prototypes\seller\Store;
use app\models\sys\users\Users;
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
 *
 * @property Users $initiatorUser Пользователь, создавший заказ
 * @property Store $storeStore Магазин поставки заказа
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

	/**s
	 * @return ActiveQuery
	 */
	public function getInitiatorUser():ActiveQuery {
		return $this->hasOne(Users::class, ['id' => 'initiator']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getStoreStore():ActiveQuery {
		return $this->hasOne(Store::class, ['id' => 'store']);
	}
}
