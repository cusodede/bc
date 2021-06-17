<?php

namespace app\modules\fraud\components\queue;

use app\modules\fraud\components\FraudValidator;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Class FraudValidatorRepeatJob
 * @package app\modules\fraud\components\queue
 */
class FraudValidatorRepeatJob extends BaseObject implements JobInterface
{
	public $fraudStepId;

	public function execute($queue) {
		/**
		 * @var FraudValidator $validator
		 */
		$validator = new $this->validatorClass();
		$validator->repeatValidate($this->fraudStepId);
	}
}