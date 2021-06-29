<?php
declare(strict_types = 1);

namespace app\models\billing_journal\active_record;

use app\models\abonents\active_record\Abonents;
use app\models\abonents\active_record\RelAbonentsToProducts;
use app\models\billing_journal\BillingJournalSearch;
use app\models\products\active_record\Products;
use app\components\db\ActiveRecordTrait;
use Exception;
use pozitronik\helpers\Utils;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "billing_journal".
 *
 * @property string $id
 * @property int $rel_abonents_to_products_id
 * @property string $price
 * @property int $status_id
 * @property string $try_date
 * @property string $created_at
 *
 * @property-read RelAbonentsToProducts $relatedAbonentsToProducts
 * @property-read Abonents $relatedAbonent
 * @property-read Products $relatedProduct
 */
class BillingJournal extends ActiveRecord
{
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'billing_journal';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['!id', 'rel_abonents_to_products_id', 'status_id', 'try_date'], 'required'],
			[['rel_abonents_to_products_id', 'status_id'], 'integer'],
			[['price'], 'number'],
			[['try_date', 'created_at'], 'safe'],
			[['!id'], 'string', 'max' => 36],
			[['!id'], 'unique'],
		];
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	public function beforeValidate(): bool
	{
		if ($this->isNewRecord && !$this instanceof BillingJournalSearch) {
			$this->id = Utils::gen_uuid();
		}

		return parent::beforeValidate();
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id'                          => 'ID',
			'rel_abonents_to_products_id' => 'Rel Abonents To Products ID',
			'price'                       => 'Величина списания',
			'status_id'                   => 'Статус операции',
			'try_date'                    => 'Дата операции',
			'created_at'                  => 'Created At',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonentsToProducts(): ActiveQuery
	{
		return $this->hasOne(RelAbonentsToProducts::class, ['id' => 'rel_abonents_to_products_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonent(): ActiveQuery
	{
		return $this->hasOne(Abonents::class, ['id' => 'abonent_id'])->via('relatedAbonentsToProducts');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProduct(): ActiveQuery
	{
		return $this->hasOne(Products::class, ['id' => 'product_id'])->via('relatedAbonentsToProducts');
	}
}
