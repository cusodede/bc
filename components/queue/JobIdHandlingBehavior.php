<?php
declare(strict_types = 1);

namespace app\components\queue;

use yii\base\Behavior;
use yii\queue\ExecEvent;
use yii\queue\Queue;

/**
 *
 * Class JobIdAccessBehavior
 * @package app\components\queue
 */
class JobIdHandlingBehavior extends Behavior
{
	/**
	 * @var string|null идентификатор джобы, в рамках которой работает очередь.
	 */
	private ?string $jobId;

	/**
	 * {@inheritdoc}
	 */
	public function events(): array
	{
		return [
			Queue::EVENT_BEFORE_EXEC => 'initId',
			Queue::EVENT_AFTER_EXEC  => 'unsetId',
			Queue::EVENT_AFTER_ERROR => 'unsetId',
		];
	}

	/**
	 * @param ExecEvent $event
	 */
	public function initId(ExecEvent $event): void
	{
		$this->jobId = $event->id;
	}

	public function unsetId(): void
	{
		$this->jobId = null;
	}

	/**
	 * @return string|null
	 */
	public function getJobId(): ?string
	{
		return $this->jobId;
	}
}