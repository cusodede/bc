<?php
declare(strict_types = 1);

namespace app\models\prototypes\seller\active_record;


use pozitronik\helpers\DateHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sellers".
 *
 * @property int $id
 * @property string $name Имя продавца
 * @property string $create_date Дата регистрации
 * @property int $deleted
 */
class Sellers extends ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'sellers';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['name', 'create_date'], 'required'],
			[['create_date'], 'safe'],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()],
			[['deleted'], 'integer'],
			[['name'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'name' => 'Имя продавца',
			'create_date' => 'Дата регистрации',
			'deleted' => 'Deleted',
		];
	}
}
