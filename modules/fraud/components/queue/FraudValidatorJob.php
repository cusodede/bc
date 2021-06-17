<?php

namespace app\modules\fraud\components\queue;

use app\modules\fraud\components\FraudValidator;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class FraudValidatorJob extends BaseObject implements JobInterface
{
	public int $productOrderId;
	public string $validatorClass;

	public function execute($queue) {
		/**
		 * @var FraudValidator $validator
		 */
		$validator = new $this->validatorClass();
		$validator->validate($this->productOrderId);
	}
}