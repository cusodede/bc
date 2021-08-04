<?php
declare(strict_types = 1);

namespace app\models\ticket;

use app\components\helpers\DateHelper;
use app\models\ticket\active_record\TicketJournal;
use yii\db\ActiveQuery;

/**
 * @property-read bool $isConnectNeeded
 * @property-read bool $isCompleted
 */
trait TicketTrait
{
	/**
	 * @param int $code
	 * @param int $status
	 * @param int|null $changedBy
	 * @param array|null $userData
	 */
	public function pushStatus(int $code, int $status, ?int $changedBy = null, ?array $userData = null): void
	{
		if (null !== $changedBy) {
			$userData['changed_by'] = $changedBy;
		}

		$ticketJournal = new TicketJournal([
			'procedure_code' => $code,
			'status'         => $status,
			'user_data'      => $userData
		]);
		/** @see beforeValidate() */
		$ticketJournal->validate(['id']);

		$this->link('relatedTicketJournals', $ticketJournal);
	}

	/**
	 * @param Ticket $ticket
	 */
	public function setRelatedTicket(Ticket $ticket): void
	{
		$this->link('relatedTicket', $ticket);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedTicket(): ActiveQuery
	{
		return $this->hasOne(Ticket::class, ['id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedTicketJournals(): ActiveQuery
	{
		return $this->hasMany(TicketJournal::class, ['ticket_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedLastTicketJournal(): ActiveQuery
	{
		return $this->hasOne(TicketJournal::class, ['ticket_id' => 'id'])->orderBy(['created_at' => SORT_DESC]);
	}

	/**
	 * Финализируем выполнение тикета.
	 */
	public function makeComplete(): void
	{
		$this->relatedTicket->setAndSaveAttribute('completed_at', DateHelper::lcDate());
	}

	/**
	 * @return bool
	 */
	public function getIsCompleted(): bool
	{
		return null !== $this->relatedTicket->completed_at;
	}
}