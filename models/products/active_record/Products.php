<?php
declare(strict_types = 1);

namespace app\models\products\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\partners\active_record\Partners;
use app\models\products\EnumProductsPaymentPeriods;
use app\models\sys\users\active_record\Users;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name Название продукта
 * @property float $price
 * @property string $description Описание продукта
 * @property string $ext_description Полное описание продукта
 * @property int|null $type_id id типа (подписка, бандл и т.д)
 * @property int $user_id id пользователя, создателя
 * @property int $partner_id id партнера, к кому привязан
 * @property string $start_date Дата начала действия продукта
 * @property string $end_date Дата окончания действия продукта
 * @property int $payment_period Периодичность списания
 * @property int $deleted Флаг удаления
 * @property string $created_at Дата создания продукта
 * @property string $updated_at Дата обновления партнера
 *
 * @property Partners $relatedPartner
 * @property Users $relatedUser
 */
class Products extends ActiveRecord
{
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'products';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['user_id'], 'default', 'value' => Yii::$app->user->id],
			[['name', 'description', 'ext_description', 'user_id', 'partner_id', 'type_id'], 'required', 'message' => 'Заполните {attribute}.'],
			[['type_id', 'user_id', 'partner_id', 'deleted', 'payment_period'], 'integer'],
			[['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
			[['price'], 'number', 'min' => 0, 'max' => 999999],
			[['price'], 'default', 'value' => 0],
			[['name'], 'string', 'max' => 64, 'min' => 3],
			[['description'], 'string', 'max' => 255],
			[['ext_description'], 'string'],
			[['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partners::class, 'targetAttribute' => ['partner_id' => 'id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
			[['name'], 'unique', 'targetAttribute' => ['name', 'partner_id', 'type_id'], 'message' => 'Такой продукт уже существует.'],
			['payment_period', 'in', 'range' => array_keys(EnumProductsPaymentPeriods::mapData())],
			[['start_date', 'end_date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => 'ID',
			'name' => 'Наименование',
			'description' => 'Краткое описание продукта',
			'ext_description' => 'Полное описание продукта',
			'type_id' => 'Тип продукта',
			'user_id' => 'Пользователь',
			'partner_id' => 'Партнер',
			'start_date' => 'Начало действия',
			'end_date' => 'Окончания действия',
			'payment_period' => 'Периодичность списания',
			'deleted' => 'Флаг удаления',
			'created_at' => 'Дата создания',
			'updated_at' => 'Дата обновления',
			'price' => 'Цена',
		];
	}

	/**
	 * Gets query for [[Partner]].
	 *
	 * @return ActiveQuery
	 */
	public function getRelatedPartner(): ActiveQuery
	{
		return $this->hasOne(Partners::class, ['id' => 'partner_id']);
	}

	/**
	 * Gets query for [[User]].
	 *
	 * @return ActiveQuery
	 */
	public function getRelatedUser(): ActiveQuery
	{
		return $this->hasOne(Users::class, ['id' => 'user_id']);
	}
}
