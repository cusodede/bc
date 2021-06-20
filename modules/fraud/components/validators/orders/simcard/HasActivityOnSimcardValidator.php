<?php
declare(strict_types = 1);

namespace app\modules\fraud\components\validators\orders\simcard;

use app\modules\fraud\components\FraudException;
use app\modules\fraud\components\FraudValidator;

/**
 * Class HasActivityOnSimcard
 * @package app\modules\fraud\components\validators\orders\simcard
 */
class HasActivityOnSimcardValidator implements FraudValidator {
	public function name():string {
		return 'Есть активность по симке(звонки, интернет)';
	}

	public function validate(int $entityId):void {
		$has = $this->hasIncomingCalls(5, 15) || $this->hasOutgoingCalls(5, 15) || $this->hasInternetTraffic(5, 15);
		if (!$has) {
			throw new FraudException("Нет активности");
		}
	}

	/** @noinspection PhpUnusedParameterInspection */
	protected function hasIncomingCalls(int $minutes, int $days):bool {
		return true;
	}

	/** @noinspection PhpUnusedParameterInspection */
	protected function hasOutgoingCalls(int $minutes, int $days):bool {
		return false;
	}

	/** @noinspection PhpUnusedParameterInspection */
	protected function hasInternetTraffic(int $MB, int $days):bool {
		return true;
	}
}