<?php
declare(strict_types = 1);

namespace app\components\exceptions;

use app\components\helpers\ArrayHelper;
use app\components\helpers\TemporaryHelper;
use Exception;
use InvalidArgumentException;
use Throwable;

/**
 * Class ValidationErrors
 * @package app\models\sys
 */
class ValidateException extends Exception {
	protected array $errors;

	/**
	 * Construct the exception. Note: The message is NOT binary safe.
	 * @link https://php.net/manual/en/exception.construct.php
	 * @param string $message [optional] The Exception message to throw.
	 * @param int $code [optional] The Exception code.
	 * @param null|Throwable $previous [optional] The previous throwable used for the exception chaining.
	 */
	public function __construct(array $errors, $message = "Ошибка валидации. ", $code = 0, Throwable $previous = null) {
		if ([] === $errors) {
			throw new InvalidArgumentException("Список ошибок не может быть пустым");
		}
		$this->errors = $errors;
		/**
		 * Например, в тестах не хватает полного описания ошибки
		 * @TODO доработать вывод ошибки в тестах
		 */
		if (YII_DEBUG) {
			$message .= TemporaryHelper::Errors2String($errors);
		}
		parent::__construct($message, $code, $previous);
	}

	/**
	 * @return array
	 */
	public function getErrors():array {
		return $this->errors;
	}

    /**
     * @param array $map
     * @return array
     */
    public function mapErrors(array $map = []):array {
		return ArrayHelper::renameKeysByMap($this->errors, $map);
	}
}
