<?php
declare(strict_types = 1);

namespace app\components\queue;

use pozitronik\helpers\Utils;
use yii\base\InvalidArgumentException;
use yii\db\Query;
use yii\queue\db\Queue;

/**
 * Class DbQueue
 * @package app\components\queue
 *
 * @property-read array $lastStatusPayload атрибуты статуса последней запрошенной джобы.
 */
class DbQueue extends Queue
{
	public const ERR_OK = 0;

	private array $_lastStatusPayload = [];

	/**
	 * {@inheritdoc}
	 */
	protected function pushMessage($message, $ttr, $delay, $priority): string
	{
		$id = Utils::gen_uuid();

		$this->db->createCommand()
			->insert($this->tableName, [
				'id' => $id,
				'channel' => $this->channel,
				'job' => $message,
				'pushed_at' => time(),
				'ttr' => $ttr,
				'delay' => $delay,
				'priority' => $priority ?: 1024
			])
			->execute();

		return $id;
	}

	/**
	 * @param string $id
	 * @return int
	 * @throws InvalidArgumentException
	 */
	public function status($id): int
	{
		$this->_lastStatusPayload = (new Query())->from($this->tableName)->where(['id' => $id])->one($this->db);
		if (false === $this->_lastStatusPayload) {
			$this->_lastStatusPayload = [];
		}

		if ([] === $this->_lastStatusPayload) {
			if ($this->deleteReleased) {
				return self::STATUS_DONE;
			}

			throw new InvalidArgumentException("Unknown message ID: $id.");
		}

		if (null === $this->_lastStatusPayload['reserved_at']) {
			return self::STATUS_WAITING;
		}

		if (null === $this->_lastStatusPayload['done_at']) {
			return self::STATUS_RESERVED;
		}

		return self::STATUS_DONE;
	}

	public function getLastStatusPayload(): array
	{
		return $this->_lastStatusPayload;
	}
}