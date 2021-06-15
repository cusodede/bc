<?php
declare(strict_types = 1);

namespace app\models\abonents\active_record;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\products\active_record\ProductStatuses;
use app\models\products\Products;
use pozitronik\core\traits\Relations;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "relation_abonents_to_products".
 *
 * @property int $id
 * @property int $abonent_id
 * @property int $product_id
 * @property string $created_at
 *
 * @property ProductStatuses[] $relatedProductStatuses
 * @property Abonents $relatedAbonent
 * @property Products $relatedProduct
 */
class RelAbonentsToProducts extends ActiveRecord
{
	use Relations;
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'relation_abonents_to_products';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['abonent_id', 'product_id'], 'required'],
			[['abonent_id', 'product_id'], 'integer'],
			[['abonent_id', 'product_id'], 'unique', 'targetAttribute' => ['abonent_id', 'product_id']],
			[['abonent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Abonents::class, 'targetAttribute' => ['abonent_id' => 'id']],
			[['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
			[['created_at'], 'safe']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id'         => 'ID',
			'abonent_id' => 'Abonent ID',
			'product_id' => 'Product ID',
			'created_at' => 'Created At',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProductStatuses(): ActiveQuery
	{
		return $this->hasMany(ProductStatuses::class, ['rel_abonents_to_products_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonent(): ActiveQuery
	{
		return $this->hasOne(Abonents::class, ['id' => 'abonent_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProduct(): ActiveQuery
	{
		return $this->hasOne(Products::class, ['id' => 'product_id']);
	}
}
