<?php
declare(strict_types = 1);

namespace app\modules\api\exceptions;

use app\components\exceptions\ExtendedThrowable;
use Throwable;
use yii\base\UserException;

/**
 * Class ValidationException
 * @package app\modules\api\exceptions
 */
class ValidationException extends UserException implements ExtendedThrowable
{
	private array $_errors;

	/**
	 * ValidationException constructor.
	 * @param array $errors
	 * @param Throwable|null $previous
	 */
	public function __construct(array $errors, Throwable $previous = null)
	{
		parent::__construct('', 400, $previous);

		$this->_errors = $errors;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getErrorCode(): string
	{
		return 'ERR_VALIDATION';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUserFriendlyMessage(): string
	{
		return '';
	}

	/**
	 * @return array
	 */
	public function getErrors(): array
	{
		$array = [];
		foreach ($this->_errors as $attr => $error) {
			$array[] = ['field' => $attr, 'error' => is_array($error) ? current($error) : $error];
		}

		return $array;
	}
}