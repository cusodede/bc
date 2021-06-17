<?php

namespace app\modules\fraud\components;

interface FraudValidator
{
	/**
	 * Название валидатора
	 * @return string
	 */
	public function name() : string;

	/**
	 * Например, ID заказа
	 * @param int $entityId
	 */
	public function validate(int $entityId);

	/**
	 * Если нужно повторно запустить проверку, передается id шага
	 * @param int $fraudStepId
	 * @return mixed
	 */
	public function repeatValidate(int $fraudStepId);
}
