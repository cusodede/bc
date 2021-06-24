<?php
declare(strict_types = 1);

namespace app\models\billing_journal\active_record;

use app\models\abonents\active_record\RelAbonentsToProducts;
use app\models\core\prototypes\ActiveRecordTrait;
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
		if ($this->isNewRecord) {
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
			'id' => 'ID',
			'rel_abonents_to_products_id' => 'Rel Abonents To Products ID',
			'price' => 'Price',
			'status_id' => 'Status ID',
			'try_date' => 'Try Date',
			'created_at' => 'Created At',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedAbonentsToProducts(): ActiveQuery
	{
		return $this->hasOne(RelAbonentsToProducts::class, ['id' => 'rel_abonents_to_products_id']);
	}
}
