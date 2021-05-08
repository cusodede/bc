<?php
declare(strict_types = 1);

namespace app\models\prototypes\merch\active_record;

use app\models\core\prototypes\ActiveRecordTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "merch".
 *
 * @property int $id
 * @property int $name Название товара
 * @property string $item_class Класс товара
 * @property string $create_date Дата регистрации
 * @property int $deleted
 */
class Merch extends ActiveRecord {
	use ActiveRecordTrait;
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'merch';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['name', 'item_class', 'create_date'], 'required'],
			[['name', 'deleted'], 'integer'],
			[['create_date'], 'safe'],
			[['item_class'], 'string', 'max' => 255],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',
			'name' => 'Название товара',
			'item_class' => 'Класс товара',
			'create_date' => 'Дата регистрации',
			'deleted' => 'Deleted',
		];
	}
}
