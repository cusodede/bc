<?php
declare(strict_types = 1);

namespace app\models\ticket\active_record\relations;

use app\models\billing_journal\BillingJournal;
use app\models\ticket\Ticket;
use pozitronik\relations\traits\RelationsTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class RelTicketToBilling
 * @package app\models\ticket\active_record\relations
 */
class RelTicketToBilling extends ActiveRecord
{
	use RelationsTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'relation_ticket_to_billing';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['ticket_id', 'billing_id'], 'required'],
			[['ticket_id', 'billing_id'], 'integer'],
			[['ticket_id', 'billing_id'], 'unique', 'targetAttribute' => ['ticket_id', 'billing_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels(): array
	{
		return [
			'id'         => 'ID',
			'ticket_id'  => 'Ticket ID',
			'billing_id' => 'Billing ID',
		];
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedTicket(): ActiveQuery
	{
		return $this->hasOne(Ticket::class, ['id' => 'ticket_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedBillingJournal(): ActiveQuery
	{
		return $this->hasOne(BillingJournal::class, ['id' => 'billing_id']);
	}
}