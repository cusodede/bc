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
		$this->min      = 10;
		$this->upper    = 2;
		$this->lower    = 2;
		$this->digit    = 2;
		$this->special  = 2;
		$this->hasUser  = false;
		$this->hasEmail = false;

		parent::init();
	}
}