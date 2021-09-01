<?php
declare(strict_types = 1);

namespace app\models\contracts\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\products\Products;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "contracts".
 *
 * @property int $id
 * @property string $contract_number № договора
 * @property string $contract_number_nfs № контракта
 * @property string $signing_date Дата подписания договора
 * @property int $deleted Флаг активности
 * @property string $created_at Дата создания договора
 * @property string $updated_at Дата обновления договора
 * @property Products[] $relatedProducts Продукты договора
 */
class Contracts extends ActiveRecord
{
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'contracts';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['contract_number', 'contract_number_nfs', 'signing_date'], 'required'],
			[['deleted'], 'integer'],
			[['signing_date', 'created_at', 'updated_at'], 'safe'],
			[['contract_number', 'contract_number_nfs'], 'string', 'max' => 11],
			[['signing_date'], 'date', 'format' => 'yyyy-mm-dd'],
			[['relatedProducts'], 'safe']
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => 'ID',
			'contract_number' => '№ договора',
			'contract_number_nfs' => '№ контракта',
			'signing_date' => 'Дата подписания договора',
			'relatedProducts' => 'Продукты',
			'deleted' => 'Флаг удаления',
			'created_at' => 'Дата создания',
			'updated_at' => 'Дата обновления',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedContractsToProducts(): ActiveQuery
	{
		return $this->hasMany(RelContractsToProducts::class, ['contract_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProducts(): ActiveQuery
	{
		return $this->hasMany(Products::class, ['id' => 'products_id'])->via('relatedContractsToProducts');
	}

	/**
	 * @param mixed $relatedProducts
	 * @throws Throwable
	 */
	public function setRelatedProducts(mixed $relatedProducts): void
	{
		if (empty($relatedProducts)) {
			RelContractsToProducts::clearLinks($this);
		} else {
			RelContractsToProducts::linkModels($this, $relatedProducts);
		}
	}

}
