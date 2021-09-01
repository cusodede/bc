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
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => 'ID',
			'description' => 'Description',
			'calc_formula' => 'Calc Formula',
			'value' => 'Value',
			'deleted' => 'Deleted',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRelatedProducts(): ActiveQuery
	{
		return $this->hasMany(Products::class, ['refsharing_rates_id' => 'id']);
	}
}
