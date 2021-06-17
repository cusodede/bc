<?php
declare(strict_types = 1);

namespace app\models\product\active_record;

use app\models\product\Product;
use app\models\product\active_record\relations\RelOrderToProduct;
use app\models\store\Stores;
use app\models\sys\users\Users;
use app\modules\fraud\components\behaviours\ProductOrderSimcardAsyncBehaviour;
use app\modules\status\models\traits\StatusesTrait;
use pozitronik\helpers\DateHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product_order".
 *
 * @property int $id
 * @property int $initiator Заказчик
 * @property int $store Магазин
 * @property int $status Статус
 * @property string $create_date Дата регистрации
 * @property int $deleted
 *
 * @property RelOrderToProduct[] $relatedOrderToProduct Связь к промежуточной таблице к товарам заказа
 * @property Product[] $product Товары в заказе
 *
 * @property Users $initiatorUser Пользователь, создавший заказ
 * @property Stores $storeStore Магазин поставки заказа
 */
class ProductOrderAR extends ActiveRecord {
	use StatusesTrait;

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return [
			ProductOrderSimcardAsyncBehaviour::class
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'product_order';
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
	public function getRelatedOrderToProduct():ActiveQuery {
		return $this->hasMany(RelOrderToProduct::class, ['order_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getProduct():ActiveQuery {
		return $this->hasMany(Product::class, ['id' => 'product_id'])->via('relatedOrderToProduct');
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

}
