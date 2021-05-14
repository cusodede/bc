<?php
declare(strict_types = 1);

namespace app\models\products\active_record;

use app\models\partners\active_record\Partners;
use app\models\ref_products_types\active_record\RefProductsTypes;
use app\models\sys\users\active_record\Users;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name Название продукта
 * @property string|null $description Описание продукта
 * @property int|null $type_id id типа (подписка, бандл и т.д)
 * @property int $user_id id пользователя, создателя
 * @property int $partner_id id партнера, к кому привязан
 * @property int $active Флаг активности
 * @property string $created_at Дата создания партнера
 *
 * @property Partners $partner
 * @property RefProductsTypes $type
 * @property Users $user
 */
class Products extends ActiveRecord
{
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
			[['name', 'user_id', 'partner_id'], 'required'],
			[['type_id', 'user_id', 'partner_id', 'active'], 'integer'],
			[['created_at'], 'safe'],
			[['name'], 'string', 'max' => 64],
			[['description'], 'string', 'max' => 255],
			[['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partners::class, 'targetAttribute' => ['partner_id' => 'id']],
			[['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefProductsTypes::class, 'targetAttribute' => ['type_id' => 'id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'type_id' => 'Type ID',
			'user_id' => 'User ID',
			'partner_id' => 'Partner ID',
			'active' => 'Active',
			'created_at' => 'Created At',
		];
	}

	/**
	 * Gets query for [[Partner]].
	 *
	 * @return ActiveQuery
	 */
	public function getPartner(): ActiveQuery
	{
		return $this->hasOne(Partners::class, ['id' => 'partner_id']);
	}

	/**
	 * Gets query for [[Type]].
	 *
	 * @return ActiveQuery
	 */
	public function getType(): ActiveQuery
	{
		return $this->hasOne(RefProductsTypes::class, ['id' => 'type_id']);
	}

	/**
	 * Gets query for [[User]].
	 *
	 * @return ActiveQuery
	 */
	public function getUser(): ActiveQuery
	{
		return $this->hasOne(Users::class, ['id' => 'user_id']);
	}
}
