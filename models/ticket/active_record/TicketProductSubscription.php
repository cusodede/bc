<?php
declare(strict_types = 1);

namespace app\models\ticket\active_record;

use app\components\db\ActiveRecordTrait;
use app\models\abonents\Abonents;
use app\models\abonents\RelAbonentsToProducts;
use app\models\billing_journal\BillingJournal;
use app\models\products\Products;
use app\models\ticket\active_record\relations\RelTicketToBilling;
use app\models\ticket\TicketTrait;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ticket_product_subscription".
 *
 * @property string $id
 * @property int $action
 * @property int $rel_abonents_to_products_id
 *
 * @property Abonents $relatedAbonent
 * @property Products $relatedProduct
 * @property BillingJournal $relatedSucceedBilling
 * @property RelAbonentsToProducts $relatedAbonentsToProducts
 * @property Ticket $relatedTicket
 * @property RelTicketToBilling $relatedTicketToBilling
 * @property TicketJournal $relatedLastTicketJournal
 * @property TicketJournal[] $relatedTicketJournals
 */
class TicketProductSubscription extends ActiveRecord
{
	use ActiveRecordTrait;
	use TicketTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'ticket_product_subscription';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['!id', 'action'], 'required'],
			[['action', 'rel_abonents_to_products_id'], 'integer'],
			[['!id'], 'string', 'max' => 36],
			[['!id'], 'unique'],
			[['!id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::class, 'targetAttribute' => ['id' => 'id']],
			[['rel_abonents_to_products_id'], 'exist', 'skipOnError' => true, 'targetClass' => RelAbonentsToProducts::class, 'targetAttribute' => ['rel_abonents_to_products_id' => 'id']]
		];
	}

	/**
	 * @param RelAbonentsToProducts|array $relation
	 */
	public function setRelatedAbonentsToProducts($relation): void
	{
		if (is_array($relation)) {
			/** @noinspection CallableParameterUseCaseInTypeContextInspection метод находится в скоупе трейта, поэтому и ругается */
			$relation = RelAbonentsToProducts::Upsert($relation);
		}

		$this->link('relatedAbonentsToProducts', $relation);
	}

	/**
	 * @param mixed $related
	 * @throws Throwable
	 */
	public function setRelatedBilling(mixed $related): void
	{
		RelTicketToBilling::linkModel($this, $related);
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

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedSucceedBilling(): ActiveQuery
	{
		return $this->hasOne(BillingJournal::class, ['id' => 'billing_id'])->via('relatedTicketToBilling')->orderBy(['created_at' => SORT_DESC]);
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
	public function getRelatedTicketToBilling(): ActiveQuery
	{
		return $this->hasMany(RelTicketToBilling::class, ['ticket_id' => 'id']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id' => 'ID',
			'action' => 'Action',
			'rel_abonents_to_products_id' => 'Rel Abonents To Products ID',
		];
	}
}
