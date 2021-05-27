<?php
declare(strict_types = 1);

namespace app\models\dealers\active_record;

use app\models\core\prototypes\ActiveRecordTrait;
use app\modules\history\behaviors\HistoryBehavior;
use pozitronik\helpers\DateHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dealers".
 *
 * @property int $id
 * @property string $name Название дилера
 * @property string $code Код дилера
 * @property string $client_code Код клиента
 * @property int $group Группа
 * @property int $branch Филиал
 * @property int $type Тип
 * @property string $create_date Дата регистрации
 * @property int $daddy ID зарегистрировавшего/проверившего пользователя
 * @property int $deleted
 */
class DealersAR extends ActiveRecord {
	use ActiveRecordTrait;

	/**
	 * @inheritDoc
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
	public static function tableName():string {
		return 'dealers';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules():array {
		return [
			[['name', 'code', 'client_code', 'group', 'branch'], 'required'],
			[['group', 'branch', 'type', 'daddy', 'deleted'], 'integer'],
			[['create_date'], 'safe'],
			[['create_date'], 'default', 'value' => DateHelper::lcDate()],/*если делаем так, то не ставим required*/
			[['name'], 'string', 'max' => 255],
			[['code'], 'string', 'max' => 4],
			[['client_code'], 'string', 'max' => 9],
			[['code'], 'unique'],
			[['client_code'], 'unique'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels():array {
		return [
			'id' => 'ID',
			'name' => 'Название дилера',
			'code' => 'Код дилера',
			'client_code' => 'Код клиента',
			'group' => 'Группа',
			'branch' => 'Филиал',
			'type' => 'Тип',
			'create_date' => 'Дата регистрации',
			'daddy' => 'ID зарегистрировавшего/проверившего пользователя',
			'deleted' => 'Deleted',
		];
	}
}
