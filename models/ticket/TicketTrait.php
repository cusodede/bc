<?php
declare(strict_types = 1);

namespace app\models\ticket;

use app\components\exceptions\ExtendedThrowable;
use app\components\helpers\DateHelper;
use Exception;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;

/**
 * @property Ticket $relatedTicket
 * @property-read bool $isCompleted
 */
trait TicketTrait
{
	public bool $forceSave = false;

	/**
	 * @param int $id
	 * @param bool $forceSave
	 * @throws StaleObjectException
	 * @throws Throwable
	 */
	public function updateStage(int $id, bool $forceSave = null): void
	{
		$this->relatedTicket->stage_id = $id;

		$stepData = ['stage_id' => $id, 'timestamp' => time()];
		$this->relatedTicket->journal_data = ArrayHelper::merge($this->relatedTicket->journal_data, ['history' => [$stepData]]);

		if ($this->forceSaveIsTrue($forceSave)) {
			$this->relatedTicket->update();
		}
	}

	/**
	 * @param array $data
	 * @param bool $forceSave
	 * @throws StaleObjectException
	 * @throws Throwable
	 */
	public function log(array $data, bool $forceSave = null): void
	{
		$this->relatedTicket->journal_data = ArrayHelper::merge($this->relatedTicket->journal_data, $data);

		if ($this->forceSaveIsTrue($forceSave)) {
			$this->relatedTicket->update(true, ['journal_data']);
		}
	}

	/**
	 * @param ExtendedThrowable|null $e
	 * @param bool $forceSave
	 * @throws StaleObjectException
	 * @throws Throwable
	 */
	public function markStageFailed(?Throwable $e = null, bool $forceSave = null): void
	{
		$this->relatedTicket->status = Ticket::STATUS_ERROR;
		if (null !== $e) {
			$this->log(['error' => $this->transformException($e)], false);
		}

		if ($this->forceSaveIsTrue($forceSave)) {
			$this->relatedTicket->update();
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
			'code' => $e->getCode(),
			'baseMessage' => $e->getMessage(),
			'userMessage' => ''
		];

		if ($e instanceof ExtendedThrowable) {
			$errorData['code']        = $e->getErrorCode();
			$errorData['userMessage'] = $e->getUserFriendlyMessage();
		}

		return $errorData;
	}

	/**
	 * Завершаем обработку тикета и фиксируем дату закрытия.
	 */
	public function close(): void
	{
		$this->relatedTicket->completed_at = DateHelper::lcDate();
		$this->relatedTicket->update();
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
	 * @return bool
	 */
	public function getIsCompleted(): bool
	{
		return null !== $this->relatedTicket->completed_at;
	}

	/**
	 * @return bool
	 */
	public function getIsStatusOk(): bool
	{
		return Ticket::STATUS_OK === $this->relatedTicket->status;
	}

	/**
	 * @return string|null
	 * @throws Exception
	 */
	public function extractErrorDescriptionFromJournal(): ?string
	{
		return ArrayHelper::getValue($this->relatedTicket->journal_data, 'error.userMessage');
	}

	/**
	 * @param bool|null $localStatus
	 * @return bool
	 */
	private function forceSaveIsTrue(bool $localStatus = null): bool
	{
		return $localStatus ?? $this->forceSave;
	}
}