<?php
declare(strict_types = 1);

namespace app\components\validators;

use kartik\password\StrengthValidator;

/**
 * Проектный валидатор для проверки сложности пароля.
 */
class PasswordStrengthValidator extends StrengthValidator
{
	/**
	 * {@inheritDoc}
	 */
	public function init(): void
	{
		$this->min = 6;
		$this->upper = 1;
		$this->lower = 1;
		$this->digit = 1;
		$this->special = 1;
		$this->hasUser = false;
		$this->hasEmail = false;

		parent::init();
	}
}