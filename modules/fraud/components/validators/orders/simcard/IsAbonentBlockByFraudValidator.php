<?php
declare(strict_types = 1);

namespace app\modules\fraud\components\validators\orders\simcard;

use app\modules\fraud\components\FraudValidator;

/**
 * Class IsAbonentBlockByFraud
 * @package app\modules\fraud\components\validators\orders\simcard
 */
class IsAbonentBlockByFraudValidator implements FraudValidator {

	public function name():string {
		return "Заблокирован ли абонент за фрод.";
	}

	public function validate(int $entityId):void {

	}
}
