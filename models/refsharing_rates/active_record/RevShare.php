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
 * @property string $description Описание процентной ставки
 * @property string $calc_formula Формула расчета
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
			[['description', 'calc_formula', 'value'], 'required'],
			[['value', 'deleted'], 'integer'],
			[['created_at', 'updated_at'], 'safe'],
			[['description', 'calc_formula'], 'string', 'max' => 255],
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
			'description' => 'Описание условий ставки',
			'calc_formula' => 'Формула расчета',
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
