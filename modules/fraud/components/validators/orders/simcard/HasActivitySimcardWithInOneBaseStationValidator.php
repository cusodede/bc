<?php
declare(strict_types = 1);

namespace app\modules\fraud\components\validators\orders\simcard;

use app\modules\fraud\components\FraudValidator;

/**
 * Class HasActivitySimcardWithInOneBaseStationValidator
 * @package app\modules\fraud\components\validators\orders\simcard
 */
class HasActivitySimcardWithInOneBaseStationValidator implements FraudValidator {

	public function name():string {
		return "Проверка на активность сим-карты в течение Х дней в рамках одной базовой станции ";
	}

	public function validate(int $entityId):void {

	}
}