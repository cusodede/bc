<?php
declare(strict_types = 1);

namespace app\components\validators\inn_validator;

use yii\base\Model;
use yii\base\NotSupportedException;
use yii\validators\Validator;
use yii\web\View;

/**
 * Проверка ИНН по контрольным суммам
 * Class InnValidator
 * @package app\components\validators\inn_validator\InnValidator
 */
class InnValidator extends Validator {

	/** @inheritDoc */
	public $message = 'Неверно указан «{attribute}».';

	/**
	 * @inheritDoc
	 * @throws NotSupportedException
	 */
	public function validateAttribute($model, $attribute):void {
		if (null !== ($validation = $this->validateValue($model->{$attribute}))) {
			$this->addError($model, $attribute, $validation[0]);
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function validateValue($value):?array {
		$inn = (string)(int)$value;

		//У нас могут обрезаться ИНН начинающиеся с нуля, восстанавливаем значение
		$innLen = strlen($inn);
		if (11 === $innLen || 9 === $innLen) {
			$inn = '0'.$inn;
		}

		//ИНН юрлиц
		if (10 === strlen($inn)) {
			return $this->validateLegal($inn);
		}
		//ИНН физлиц и ИП
		if (12 === strlen($inn)) {
			return $this->validatePerson($inn);
		}
		return [$this->message, []];
	}

	/**
	 * @param string $inn
	 * @return array|null
	 */
	private function validateLegal(string $inn):?array {
		$n9 = (string)((2 * $inn[0] + 4 * $inn[1] + 10 * $inn[2] + 3 * $inn[3] +
					5 * $inn[4] + 9 * $inn[5] + 4 * $inn[6] + 6 * $inn[7] + 8 * $inn[8]) % 11) % 10;
		if ($n9 !== (int)$inn[9]) {
			return [$this->message, []];
		}
		return null;
	}

	/**
	 * @param string $inn
	 * @return array|null
	 */
	private function validatePerson(string $inn):?array {
		$n10 = (string)((7 * $inn[0] + 2 * $inn[1] + 4 * $inn[2] + 10 * $inn[3] + 3 * $inn[4] +
					5 * $inn[5] + 9 * $inn[6] + 4 * $inn[7] + 6 * $inn[8] + 8 * $inn[9]) % 11) % 10;
		$n11 = (string)((3 * $inn[0] + 7 * $inn[1] + 2 * $inn[2] + 4 * $inn[3] + 10 * $inn[4] + 3 * $inn[5] +
					5 * $inn[6] + 9 * $inn[7] + 4 * $inn[8] + 6 * $inn[9] + 8 * $inn[10]) % 11) % 10;
		if ($n10 !== (int)$inn[10] || $n11 !== (int)$inn[11]) {
			return [$this->message, []];
		}
		return null;
	}

	/**
	 * @param Model $model
	 * @param string $attribute
	 * @param View $view
	 * @return string|null
	 */
	public function clientValidateAttribute($model, $attribute, $view):?string {
		InnValidatorAssets::register($view);
		return null;
	}
}
