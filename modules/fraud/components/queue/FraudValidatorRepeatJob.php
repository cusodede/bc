<?php

namespace app\modules\fraud\components\queue;

use app\modules\fraud\components\FraudValidator;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class FraudValidatorRepeatJob
 * @package app\modules\fraud\components\queue
 */
class FraudValidatorRepeatJob extends BaseObject implements JobInterface
{
	public $fraudStepId;

	/**
	 * @param Queue $queue which pushed and is handling the job
	 * @return void result of the job execution
	 */
	public function execute($queue) {
		/**
		 * @var FraudValidator $validator
		 */
		$validator = new $this->validatorClass();
		$validator->repeatValidate($this->fraudStepId);
	}
}
