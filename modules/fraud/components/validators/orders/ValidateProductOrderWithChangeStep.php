<?php
declare(strict_types = 1);

namespace app\modules\fraud\components\validators\orders;

use app\models\product\ProductSearch;
use app\modules\fraud\components\FraudException;
use app\modules\fraud\components\FraudValidator;
use app\modules\fraud\models\FraudCheckStep;
use app\modules\fraud\models\FraudCheckStepSearch;

/**
 * Class ProductOrderValidatorWithChangeStep
 * @package app\modules\fraud\components\validators\orders
 */
class ValidateProductOrderWithChangeStep {
	private FraudValidator $validator;

	public function __construct(FraudValidator $validator) {
		$this->validator = $validator;
	}

	public function validate(int $entityId):void {
		$existentOrder = (new ProductSearch())->getExistentSimcardOrder($entityId);
		$validatorOrderRow = (new FraudCheckStepSearch())->getByOrderWithValidator($entityId, get_class($this->validator));
		$this->validateWithCatch($validatorOrderRow);
	}

	public function repeatValidate(int $stepId):void {
		$step = (new FraudCheckStepSearch())->getById($stepId);
		$this->validateWithCatch($step);
		$step->statusFail()->saveAndReturn();
	}

	protected function validateWithCatch(FraudCheckStep $step):void {
		try {
			$this->validator->validate($step->entity_id);
			$step->statusSuccess()->saveAndReturn();
		} catch (FraudException $e) {
			$step->statusFail()->saveAndReturn();
		}
	}
}