<?php
declare(strict_types = 1);

namespace app\models\site\validators;

use app\models\sys\users\Users;
use yii\validators\Validator;

/**
 * Class ExistentConfirmedPhone
 * @package app\models\site\validators
 */
class ActiveUserWithPhoneValidator extends Validator {
	/**
	 * @inheritDoc
	 */
	public function validateAttribute($model, $attribute):bool {
		$errorMessage = 'Номер не зарегистрирован';

		$existent = Users::findByLogin($model->$attribute);
		if (null === $existent) {
			$this->addError($model, $attribute, $errorMessage);
			return false;
		}
		return true;
	}
}