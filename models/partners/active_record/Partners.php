<?php
declare(strict_types = 1);

namespace app\models\partners\active_record;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\products\active_record\Products;

/**
 * This is the model class for table "partners".
 *
 * @property int $id
 * @property string $name Название партнера
 * @property string $created_at Дата создания партнера
 * @property int $active Флаг активности
 *
 * @property Products[] $products
 */
class Partners extends ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'partners';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['name'], 'required'],
			[['created_at'], 'safe'],
			[['active'], 'integer'],
			[['name'], 'string', 'max' => 64],
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
			'created_at' => 'Created At',
			'active' => 'Active',
		];
	}

	/**
	 * Gets query for [[Products]].
	 *
	 * @return ActiveQuery
	 */
	public function getProducts(): ActiveQuery
	{
		return $this->hasMany(Products::class, ['partner_id' => 'id']);
	}
}
