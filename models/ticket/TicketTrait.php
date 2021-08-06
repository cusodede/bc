<?php
declare(strict_types = 1);

namespace app\models\ticket;

use app\components\exceptions\ExtendedThrowable;
use app\components\helpers\DateHelper;
use app\models\ticket\active_record\TicketJournal;
use Exception;
use Throwable;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * @property-read bool $isConnectNeeded
 * @property-read bool $isCompleted
 */
trait TicketTrait
{
	/**
	 * @var array массив шагов.
	 */
	private array $_journal = [];
	/**
	 * @var int индекс текущего шага в `$_journal`
	 */
	private int $_stageIndex = -1;

	/**
	 * @param int $operationCode
	 */
	public function startStage(int $operationCode): void
	{
		$this->_journal[] = ['operationCode' => $operationCode, 'created' => DateHelper::lcDate(), 'status' => TicketJournal::STATUS_OK];

		$this->_stageIndex++;
	}

	/**
	 * @param array $data
	 * @throws Exception
	 */
	public function logData(array $data): void
	{
		$currData = ArrayHelper::getValue($this->_journal[$this->_stageIndex], 'userData', []);

		$this->_journal[$this->_stageIndex]['userData'] = ArrayHelper::merge($currData, $data);
	}

	/**
	 * @param ExtendedThrowable|null $e
	 * @throws Exception
	 */
	public function markStageFailed(?Throwable $e = null): void
	{
		$this->_journal[$this->_stageIndex]['status'] = TicketJournal::STATUS_ERROR;
		if (null !== $e) {
			
			$this->logData(['error' => $this->transformException($e)]);
		}
	}

	/**
	 * Преобразование исключения в массив для записи в лог.
	 * @param Throwable $e
	 * @return array
	 */
	public function transformException(Throwable $e): array
	{
		$errorData = [
			'code'        => $e->getCode(),
			'baseMessage' => $e->getMessage(),
			'userMessage' => ''
		];

		if ($e instanceof ExtendedThrowable) {
			$errorData['code']        = $e->getErrorCode();
			$errorData['userMessage'] = $e->getUserFriendlyMessage();
		}

		return $errorData;
	}

	public function close(): void
	{
		foreach ($this->_journal as $statusInfo) {
			$this->pushStatus(
				$statusInfo['operationCode'],
				$statusInfo['status'],
				ArrayHelper::getValue($statusInfo, 'changedBy'),
				ArrayHelper::getValue($statusInfo, 'userData', [])
			);
		}

		$this->_journal    = [];
		$this->_stageIndex = -1;

		$this->makeComplete();
	}

	/**
	 * @param int $code
	 * @param int $status
	 * @param int|null $changedBy
	 * @param array $userData
	 */
	public function pushStatus(int $code, int $status, ?int $changedBy = null, array $userData = []): void
	{
		if (null !== $changedBy) {
			$userData['changedBy'] = $changedBy;
		}

		$ticketJournal = new TicketJournal([
			'operation_code' => $code,
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

	public function extractErrorDescriptionFromJournal(): ?string
	{
		return ArrayHelper::getValue($this->relatedLastTicketJournal->user_data, 'error.userMessage');
	}
}