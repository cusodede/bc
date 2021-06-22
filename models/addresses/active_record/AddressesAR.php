<?php
declare(strict_types = 1);

namespace app\models\addresses\active_record;

use pozitronik\helpers\DateHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "addresses".
 *
 * @property int $id
 * @property string $create_date Дата создания
 * @property int $index Индекс
 * @property string $area Область
 * @property string $region Регион/район
 * @property string $city Город/н.п.
 * @property string $street Улица
 * @property string $building Дом
 * @property int $deleted
 */
class AddressesAR extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'addresses';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['index', 'city', 'street'], 'required'],
			['create_date', 'default', 'value' => DateHelper::lcDate()],
			[['index', 'deleted'], 'integer'],
			[['area', 'region', 'city', 'street', 'building'], 'string', 'max' => 255]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'create_date' => 'Дата создания',
			'index' => 'Индекс',
			'area' => 'Область',
			'region' => 'Регион/район',
			'city' => 'Город/н.п.',
			'street' => 'Улица',
			'building' => 'Дом',
			'deleted' => 'Deleted'
		];
	}
}