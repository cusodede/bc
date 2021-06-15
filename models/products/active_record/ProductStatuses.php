<?php
declare(strict_types = 1);

namespace app\models\products\active_record;

use app\models\abonents\active_record\RelAbonentsToProducts;
use app\models\core\prototypes\ActiveRecordTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product_statuses".
 *
 * @property int $id
 * @property int $rel_abonents_to_products_id
 * @property int $status_id
 * @property string $start_date
 * @property string $end_date
 * @property string $created_at
 *
 * @property RelAbonentsToProducts $relatedAbonentsToProducts
 */
class ProductStatuses extends ActiveRecord
{
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'product_statuses';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['rel_abonents_to_products_id', 'status_id', 'start_date'], 'required'],
			[['rel_abonents_to_products_id', 'status_id'], 'integer'],
			[['rel_abonents_to_products_id'], 'exist', 'skipOnError' => true, 'targetClass' => RelAbonentsToProducts::class, 'targetAttribute' => ['rel_abonents_to_products_id' => 'id']],
			[['start_date', 'end_date', 'created_at'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id'                          => 'ID',
			'rel_abonents_to_products_id' => 'Rel Abonents To Products ID',
			'status_id'                   => 'Status ID',
			'start_date'                  => 'Start Date',
			'end_date'                    => 'End Date',
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
}
