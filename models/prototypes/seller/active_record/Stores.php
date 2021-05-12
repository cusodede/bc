<?php
declare(strict_types = 1);

namespace app\models\prototypes\seller\active_record;


use pozitronik\helpers\DateHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "stores".
 *
 * @property int $id
 * @property string $name Название магазина
 * @property int $type Тип магазина
 * @property string $create_date Дата регистрации
 * @property int $deleted
 */
class Stores extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'stores';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['name', 'type', 'create_date'], 'required'],
			[['type', 'deleted'], 'integer'],
			[['create_date'], 'safe'],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()],
			[['name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'name' => 'Название магазина',
			'type' => 'Тип магазина',
			'create_date' => 'Дата регистрации',
			'deleted' => 'Deleted',
		];
	}
}
