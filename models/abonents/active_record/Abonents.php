<?php
declare(strict_types = 1);

namespace app\models\abonents\active_record;

use app\models\abonents\active_query\AbonentsActiveQuery;
use app\models\core\prototypes\ActiveRecordTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "abonents".
 *
 * @property int $id
 * @property string|null $surname Фамилия абонента
 * @property string|null $name Имя абонента
 * @property string|null $patronymic Отчество абонента
 * @property string $phone Номер абонента
 * @property int $deleted Флаг активности
 * @property string $created_at Дата создания абонента
 * @property string $updated_at Дата обновления абонента
 *
 * @property RelAbonentsToProducts[] $relatedAbonentsToProducts
 */
class Abonents extends ActiveRecord
{
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'abonents';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['phone'], 'required'],
			[['deleted'], 'integer'],
			[['created_at', 'updated_at'], 'safe'],
			[['surname', 'name', 'patronymic'], 'string', 'max' => 64],
			['phone', 'match', 'pattern' => '/^\d{11}$/', 'message' => 'Значение не верно, пример: 79050968533'],
			[['phone'], 'unique'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id'         => 'ID',
			'surname'    => 'Фамилия',
			'name'       => 'Имя',
			'patronymic' => 'Отчество',
			'phone'      => 'Телефон',
			'deleted'    => 'Флаг удаления',
			'created_at' => 'Дата создания',
			'updated_at' => 'Дата обновления',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonentsToProducts(): ActiveQuery
	{
		return $this->hasMany(RelAbonentsToProducts::class, ['abonent_id' => 'id']);
	}

	/**
	 * @return AbonentsActiveQuery
	 * @throws InvalidConfigException
	 */
	public static function find(): AbonentsActiveQuery
	{
		return Yii::createObject(AbonentsActiveQuery::class, [static::class]);
	}
}
