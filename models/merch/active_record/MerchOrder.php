<?php
declare(strict_types = 1);

namespace app\models\merch\active_record;

use app\models\merch\active_record\references\RefMerchOrderStatuses;
use app\models\merch\active_record\relations\RelMerchOrderToMerch;
use app\models\store\Stores;
use app\models\sys\users\Users;
use app\modules\status\models\traits\StatusesTrait;
use pozitronik\helpers\DateHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "merch_order".
 *
 * @property int $id
 * @property int $initiator Заказчик
 * @property int $store Магазин
 * @property int $status Статус
 * @property string $create_date Дата регистрации
 * @property int $deleted
 *
 * @property RefMerchOrderStatuses|null $referenceMerchOrderStatuses Справочник статуса
 *
 * @property RelMerchOrderToMerch[] $relatedMerchOrderToMerch Связь к промежуточной таблице к товарам заказа
 * @property Merch[] $merch Товары в заказе
 *
 * @property Users $initiatorUser Пользователь, создавший заказ
 * @property Stores $storeStore Магазин поставки заказа
 */
class MerchOrder extends ActiveRecord {
	use StatusesTrait;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'merch_order';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['initiator', 'store', 'create_date', 'status'], 'required'],
			[['initiator', 'store', 'status', 'deleted'], 'integer'],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'initiator' => 'Заказчик',
			'store' => 'Магазин',
			'status' => 'Статус',
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
		return $this->hasOne(Stores::class, ['id' => 'store']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getReferenceMerchOrderStatuses():ActiveQuery {
		return $this->hasOne(RefMerchOrderStatuses::class, ['id' => 'status']);
	}
}
