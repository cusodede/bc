<?php
declare(strict_types = 1);

namespace app\models\refsharing_rates\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\products\active_record\Products;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "refsharing_rates".
 *
 * @property int $id
 * @property int $type Тип ставки
 * @property float $ref_share Ставка рефшеринга
 * @property int $value Значение процентной ставки
 * @property int $deleted Флаг активности
 * @property string $created_at Дата создания договора
 * @property string $updated_at Дата обновления договора
 * @property int $product_id ID продукта
 * @property Products $relatedProduct
 */
class RevShare extends ActiveRecord
{
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'refsharing_rates';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['type', 'ref_share', 'value'], 'required'],
			[['type', 'value', 'deleted'], 'integer'],
			[['created_at', 'updated_at'], 'safe'],
			[['ref_share'], 'number'],
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
			'ref_share' => 'Ставка рефшеринга',
			'value' => 'Значение',
			'deleted' => 'Deleted',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRelatedProduct(): ActiveQuery
	{
		return $this->hasOne(Products::class, ['id' => 'product_id']);
	}
}
