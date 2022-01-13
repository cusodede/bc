<?php
declare(strict_types = 1);

namespace app\components\logstash;

use yii\log\FileTarget;

/**
 * Class LogStash
 * Эот лог пишет в STDOUT.
 * Формат:
 * {"t":"2021-11-20T18:52:51+00:00","level":"Info","message":"XXX","context":{"trace_id":"00-jlkjldsfa6586-jfashdjkhfsd768968-00"}}
 * Для его подключения в компоненте лог, в перемене targets пишем:
 * [
 *     'class' => LogStash::class,
 *     'categories' => ['category.example'],
 *     'logFile' => 'php://stdout',
 *     'logVars' => []
 * ]
 *
 * А в коде вызываем как обычно:
 * Yii::info('message', service.response')
 * или
 * @package app\components\logstash
 */
class LogStash extends FileTarget {

	/**
	 * @inheritDoc
	 */
	public function formatMessage($message) {
		[$text] = $message;
		return $text;
	}

	/**
	 * Этот метод помогает форматировать сообщение для logstash
	 * @param string $message
	 * @param array $context
	 * @param string $level
	 * @return string|null
	 */
	public static function create_log(string $message = '', array $context = [], string $level = 'Info'):?string {
		return json_encode(
			[
				't' => date('c'),
				'level' => $level,
				'message' => $message,
				'context' => $context
			]
		);
	}
}
