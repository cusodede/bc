<?php
declare(strict_types = 1);

namespace app\models\subscriptions\active_record;

use app\models\ref_subscription_categories\active_record\RefSubscriptionCategories;
use app\models\products\Products;
use app\models\sys\users\Users;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "subscriptions".
 *
 * @property int $id
 * @property int $product_id id продукта
 * @property int $category_id id категории подписки
 * @property int $user_id id пользователя, создателя
 * @property int $deleted Флаг активности
 * @property int $trial Триальный период
 * @property string $created_at Дата создания партнера
 * @property string $updated_at Дата обновления партнера
 *
 * @property RefSubscriptionCategories $category
 * @property Products $product
 * @property Users $user
 */
class Subscriptions extends ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'subscriptions';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['user_id'], 'default', 'value' => Yii::$app->user->id],
			[['product_id', 'category_id', 'user_id'], 'required', 'message' => 'Выберите {attribute}'],
			[['product_id', 'category_id', 'user_id', 'deleted', 'trial'], 'integer'],
			[['created_at', 'updated_at'], 'safe'],
			[['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefSubscriptionCategories::class, 'targetAttribute' => ['category_id' => 'id']],
			[['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
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
			'product_id' => 'Продукт',
			'category_id' => 'Категория подписки',
			'user_id' => 'Пользователь',
			'deleted' => 'Флаг удаления',
			'trial' => 'Триальный период',
			'created_at' => 'Дата создания',
			'updated_at' => 'Дата обновления',
		];
	}

	/**
	 * Gets query for [[Category]].
	 *
	 * @return ActiveQuery
	 */
	public function getCategory(): ActiveQuery
	{
		return $this->hasOne(RefSubscriptionCategories::class, ['id' => 'category_id']);
	}

	/**
	 * Gets query for [[Product]].
	 *
	 * @return ActiveQuery
	 */
	public function getProduct(): ActiveQuery
	{
		return $this->hasOne(Products::class, ['id' => 'product_id']);
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
