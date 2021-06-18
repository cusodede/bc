<?php
declare(strict_types = 1);

namespace app\modules\fraud\components\validators\orders\simcard;

use app\modules\fraud\components\FraudValidator;

/**
 * Class HasIncreaseBalance
 * @package app\modules\fraud\components\validators\orders\simcard
 */
class HasIncreaseBalance implements FraudValidator {

	public function name():string {
		return "Проверка на использование пополнение баланса сим-карты в течение Х (1-3 дней) дней с даты продажи";
	}

	public function validate(int $entityId):void {

	}
}