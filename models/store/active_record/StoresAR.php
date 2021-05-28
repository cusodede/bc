<?php
declare(strict_types = 1);

namespace app\models\store\active_record;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\seller\Sellers;
use app\models\store\active_record\references\RefSellingChannels;
use app\models\store\active_record\references\RefStoresTypes;
use app\models\store\active_record\relations\RelStoresToSellers;
use app\models\store\active_record\relations\RelStoresToUsers;
use app\models\sys\users\Users;
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
 * @property int $selling_channel Тип магазина
 * @property string $create_date Дата регистрации
 * @property int $deleted
 *
 * @property RefStoresTypes $refStoresTypes Тип точки (справочник)
 * @property RefSellingChannels $refSellingChannels Канал продаж (справочник)
 * @property RelStoresToSellers[] $relatedStoresToSellers Связь к промежуточной таблице к продавцам
 * @property RelStoresToUsers[] $relatedStoresToUsers Связь к промежуточной таблице к пользователям
 * @property Sellers[] $sellers Все продавцы точки
 * @property Users[] $users Пользователи, входящие под магазином
 */
class StoresAR extends ActiveRecord {
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
			[['name', 'type', 'selling_channel'], 'required'],
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
	public function getRefStoresTypes():ActiveQuery {
		return $this->hasOne(RefStoresTypes::class, ['id' => 'type']);
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

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedStoresToUsers():ActiveQuery {
		return $this->hasMany(RelStoresToUsers::class, ['store_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getUsers():ActiveQuery {
		return $this->hasMany(Users::class, ['id' => 'user_id'])->via('relatedStoresToUsers');
	}

	/**
	 * @param mixed $users
	 * @throws Throwable
	 */
	public function setUsers(array $users):void {
		RelStoresToUsers::linkModels($this, $users);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRefSellingChannels():ActiveQuery {
		return $this->hasOne(RefSellingChannels::class, ['id' => 'selling_channel']);
	}

}
