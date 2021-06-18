<?php
declare(strict_types = 1);

namespace app\modules\fraud\components\validators\orders\simcard;

use app\modules\fraud\components\FraudValidator;

/**
 * Class HasSubscriptionFeeAndHasntCalls
 * @package app\modules\fraud\components\validators\orders\simcard
 */
class HasSubscriptionFeeAndHasntCalls implements FraudValidator {

	public function name():string {
		return "Отсутствие вх. и исх. звонков, есть списание только на абонентскую плату.";
	}

	public function validate(int $entityId):void {

	}
}