<?php
declare(strict_types = 1);

namespace app\models\prototypes\seller\active_record;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\prototypes\seller\active_record\references\RefStoreTypes;
use app\models\prototypes\seller\active_record\relations\RelStoresToSellers;
use pozitronik\helpers\DateHelper;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "stores".
 *
 * @property int $id
 * @property string $name Название магазина
 * @property int $type Тип магазина
 * @property string $create_date Дата регистрации
 * @property int $deleted
 *
 * @property RefStoreTypes $refStoreType Тип точки (справочник)
 * @property RelStoresToSellers[] $relatedStoresToSellers Связь к промежуточной таблице к продавцам
 * @property Sellers[] $sellers Все продавцы точки
 */
class Stores extends ActiveRecord {
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'stores';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name', 'type'], 'required'],
			[['type', 'deleted'], 'integer'],
			[['create_date'], 'safe'],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()],
			[['name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название магазина',
			'type' => 'Тип магазина',
			'create_date' => 'Дата регистрации',
			'sellers' => 'Продавцы',
			'deleted' => 'Deleted',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRefStoreType():ActiveQuery {
		return $this->hasOne(RefStoreTypes::class, ['id' => 'type']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedStoresToSellers():ActiveQuery {
		return $this->hasMany(RelStoresToSellers::class, ['store_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getSellers():ActiveQuery {
		return $this->hasMany(Sellers::class, ['id' => 'seller_id'])->via('relatedStoresToSellers');
	}

	/**
	 * @param mixed $sellers
	 * @throws Throwable
	 */
	public function setSellers($sellers):void {
		RelStoresToSellers::linkModels($this, $sellers);
	}

}
