<?php
declare(strict_types = 1);

namespace app\models\ref_products_types\active_record;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\products\active_record\Products;

/**
 * This is the model class for table "ref_products_types".
 *
 * @property int $id
 * @property string $name
 * @property int $deleted
 *
 * @property Products[] $products
 */
class RefProductsTypes extends ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'ref_products_types';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['name'], 'required'],
			[['deleted'], 'integer'],
			[['name'], 'string', 'max' => 255],
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
			'deleted' => 'Deleted',
		];
	}

	/**
	 * Gets query for [[Products]].
	 *
	 * @return ActiveQuery
	 */
	public function getProducts(): ActiveQuery
	{
		return $this->hasMany(Products::class, ['type_id' => 'id']);
	}
}
