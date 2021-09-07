<?php
declare(strict_types = 1);

namespace app\modules\api\exceptions;

use app\components\exceptions\ExtendedThrowable;
use Exception;
use yii\web\HttpException;

/**
 * Class ValidationException
 * @package app\modules\api\exceptions
 */
class ValidationException extends HttpException implements ExtendedThrowable
{
	private array $_errors;

	/**
	 * ValidationException constructor.
	 * @param array $errors
	 * @param Exception|null $previous
	 */
	public function __construct(array $errors, Exception $previous = null)
	{
		parent::__construct(400, '', 400, $previous);

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