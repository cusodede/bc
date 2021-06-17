<?php
declare(strict_types = 1);

namespace app\modules\fraud\components\validators\orders\simcard;

use app\models\product\ProductSearch;
use app\modules\fraud\components\FraudValidator;
use app\modules\fraud\models\FraudCheckStepSearch;

class IsActiveSimcardValidator implements FraudValidator
{
	/**
	 * Например, ID заказа
	 * @param int $productOrderId
	 */
	public function validate(int $productOrderId) {
		$existentOrder = (new ProductSearch())->getExistentSimcardOrder($productOrderId);
		$validatorOrderRow = (new FraudCheckStepSearch())->getByOrderWithValidator($productOrderId, get_class($this));
	}

	public function name():string {
		return 'Активность симкарты';
	}

	/**
	 * Если нужно повторно запустить проверку, передается id шага
	 * @param int $fraudStepId
	 */
	public function repeatValidate(int $fraudStepId) {
		$stepId = (new FraudCheckStepSearch())->getById($fraudStepId);
	}
}