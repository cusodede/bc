<?php
declare(strict_types = 1);

namespace app\models\subscriptions\active_record;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\core\prototypes\RelationValidator;
use app\models\products\EnumProductsTypes;
use app\models\ref_subscription_categories\active_record\RefSubscriptionCategories;
use app\models\products\Products;
use yii\base\InvalidArgumentException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "subscriptions".
 *
 * @property int $id
 * @property int $product_id id продукта
 * @property int $category_id id категории подписки
 * @property int $trial Триальный период
 * @property int $trial_days_count
 *
 * @property RefSubscriptionCategories $category
 * @property Products $product
 */
class Subscriptions extends ActiveRecord
{
	use ActiveRecordTrait;

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
			[['product_id', 'category_id'], 'required', 'message' => 'Выберите {attribute}'],
			[['product_id', 'category_id', 'trial', 'trial_days_count'], 'integer'],
			[['created_at', 'updated_at'], 'safe'],
			[['trial_days_count'], 'default', 'value' => 0],
			[['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => RefSubscriptionCategories::class, 'targetAttribute' => ['category_id' => 'id']],
			[['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
			['product', RelationValidator::class],
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
			'trial' => 'Триальный период',
			'trial_days_count' => 'Количество пробных дней',
		];
	}

	public function init(): void
	{
		parent::init();
		$this->populateRelation('product', $this->product ?? new Products(['type_id' => EnumProductsTypes::ID_SUBSCRIPTION]));
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
	 * @param mixed $product
	 * Универсальный сеттер: в $product может придти как модель, так и её ключ (строкой или цифрой).
	 */
	public function setProduct($product): void
	{
		if (null === $product = self::ensureModel(Products::class, $product)) {
			throw new InvalidArgumentException("Невозможно обнаружить соответствующую модель");
		}
		$this->link('product', $product);
	}
}
