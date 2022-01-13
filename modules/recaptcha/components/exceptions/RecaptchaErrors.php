<?php
declare(strict_types = 1);

namespace app\modules\recaptcha\components\exceptions;

use Exception;
use Throwable;

/**
 * Class RecaptchaErrors
 * @package app\modules\recaptcha\components\exceptions
 */
class RecaptchaErrors extends Exception {
	protected array $errors = [];

	/**
	 * RecaptchaErrors constructor.
	 * @param array $errors
	 * @param string $message
	 * @param int $code
	 * @param Throwable|null $previous
	 */
	public function __construct(array $errors = [], $message = "Ошибка валидации на стороне сервиса reCaptcha: ", $code = 0, Throwable $previous = null) {
		parent::__construct($message.implode(',', $errors), $code, $previous);
	}

}
