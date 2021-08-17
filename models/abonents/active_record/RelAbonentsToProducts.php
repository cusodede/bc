<?php
declare(strict_types = 1);

namespace app\models\abonents\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\billing_journal\active_record\BillingJournal;
use app\models\products\active_record\Products;
use app\models\products\active_record\ProductsJournal;
use pozitronik\relations\traits\RelationsTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "relation_abonents_to_products".
 *
 * @property int $id
 * @property int $abonent_id
 * @property int $product_id
 * @property int $created_at [timestamp]
 *
 * @property ProductsJournal[] $relatedProductsJournal
 * @property BillingJournal[] $relatedBillingJournal
 * @property Abonents $relatedAbonent
 * @property Products $relatedProduct
 */
class RelAbonentsToProducts extends ActiveRecord
{
	use RelationsTrait;
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
			[['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']]
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
			'product_id' => 'Product ID'
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProductsJournal(): ActiveQuery
	{
		return $this->hasMany(ProductsJournal::class, ['rel_abonents_to_products_id' => 'id']);
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

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedBillingJournal(): ActiveQuery
	{
		return $this->hasMany(BillingJournal::class, ['rel_abonents_to_products_id' => 'id']);
	}
}
