<?php
declare(strict_types = 1);

namespace app\models\phones\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\phones\Phones;
use app\modules\history\behaviors\HistoryBehavior;
use pozitronik\helpers\DateHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "phones".
 *
 * @property int $id
 * @property string $phone Телефон
 * @property string $create_date Дата регистрации
 * @property int $status Статус
 * @property bool $deleted
 */
class PhonesAR extends ActiveRecord {
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName():string {
		return 'phones';
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors():array {
		return [
			'history' => [
				'class' => HistoryBehavior::class
			]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['phone'], 'required'],
			[['create_date'], 'safe'],
			[['status'], 'integer'],
			[['deleted'], 'boolean'],
			[['phone'], 'string', 'max' => 255],
			['status', 'default', 'value' => Phones::STATUS_NOT_CONFIRM],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()]
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'phone' => 'Телефон',
			'create_date' => 'Дата регистрации',
			'status' => 'Статус',
			'deleted' => 'Deleted',
		];
	}
}
