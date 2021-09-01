<?php
declare(strict_types = 1);

namespace app\models\contracts\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\products\Products;
use pozitronik\relations\traits\RelationsTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "relation_contracts_to_products".
 *
 * @property int $id
 * @property int $contract_id id договора
 * @property int $product_id id продукта
 *
 * @property Contracts $relatedContract
 * @property Products $relatedProduct
 */
class RelContractsToProducts extends ActiveRecord
{
	use RelationsTrait;
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'relation_contracts_to_products';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['contract_id', 'product_id'], 'required'],
			[['contract_id', 'product_id'], 'integer'],
			[['contract_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contracts::class, 'targetAttribute' => ['contract_id' => 'id']],
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
			'contract_id' => 'Contract ID',
			'product_id' => 'Product ID',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedContract(): ActiveQuery
	{
		return $this->hasOne(Contracts::class, ['id' => 'contract_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProduct(): ActiveQuery
	{
		return $this->hasOne(Products::class, ['id' => 'product_id']);
	}

}
