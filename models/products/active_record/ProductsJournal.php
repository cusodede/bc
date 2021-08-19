<?php
declare(strict_types = 1);

namespace app\models\products\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\abonents\active_record\RelAbonentsToProducts;
use app\models\products\ProductsJournalSearch;
use pozitronik\helpers\Utils;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "products_journal".
 *
 * @property string $id
 * @property int $rel_abonents_to_products_id
 * @property int $status_id
 * @property string $expire_date
 * @property string $created_at
 *
 * @property RelAbonentsToProducts $relatedAbonentsToProducts
 */
class ProductsJournal extends ActiveRecord
{
	use ActiveRecordTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'products_journal';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['!id', 'rel_abonents_to_products_id', 'status_id'], 'required'],
			[['rel_abonents_to_products_id', 'status_id'], 'integer'],
			[['rel_abonents_to_products_id'], 'exist', 'skipOnError' => true, 'targetClass' => RelAbonentsToProducts::class, 'targetAttribute' => ['rel_abonents_to_products_id' => 'id']],
			[['expire_date', 'created_at'], 'safe'],
			[['!id'], 'string', 'max' => 36],
			[['!id'], 'unique'],
		];
	}

	/**
	 * @return bool
	 */
	public function beforeValidate(): bool
	{
		if ($this->isNewRecord && !$this instanceof ProductsJournalSearch) {
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
			'status_id' => 'Статус подключения',
			'expire_date' => 'Срок действия',
			'created_at' => 'Дата заведения',
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
