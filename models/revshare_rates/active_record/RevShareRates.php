<?php
declare(strict_types = 1);

namespace app\models\revshare_rates\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\products\active_record\Products;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "revshare_rates".
 *
 * @property int $id
 * @property int $type Тип ставки
 * @property float $rate Процентная ставка
 * @property int $condition_value Пороговое значение для активации ставки
 * @property bool $deleted Флаг активности
 * @property string $created_at Дата создания договора
 * @property string $updated_at Дата обновления договора
 * @property int $product_id ID продукта
 *
 * @property Products $relatedProduct
 */
class RevShareRates extends ActiveRecord
{
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'revshare_rates';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['type', 'rate', 'condition_value'], 'required'],
			[['type', 'condition_value'], 'integer'],
			[['created_at', 'updated_at'], 'safe'],
			[['rate'], 'number'],
			[['deleted'], 'boolean'],
			[['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => 'ID',
			'product_id' => 'ID product',
			'type' => 'Тип ставки',
			'rate' => 'Процентная ставка',
			'condition_value' => 'Пороговое значение для активации ставки',
			'deleted' => 'Deleted',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProduct(): ActiveQuery
	{
		return $this->hasOne(Products::class, ['id' => 'product_id']);
	}
}
