<?php

namespace app\modules\fraud\components\queue;

use app\modules\fraud\components\FraudValidator;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class FraudValidatorJob
 * @package app\modules\fraud\components\queue
 */
class FraudValidatorJob extends BaseObject implements JobInterface
{
	public int $entityId;
	public string $validatorClass;

	/**
	 * @param Queue $queue which pushed and is handling the job
	 * @return void result of the job execution
	 */
	public function execute($queue) {
		/**
		 * @var FraudValidator $validator
		 */
		$validator = new $this->validatorClass();
		$validator->validate($this->entityId);
	}
}
