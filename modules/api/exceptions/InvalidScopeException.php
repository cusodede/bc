<?php
declare(strict_types = 1);

namespace app\modules\api\exceptions;

use Throwable;
use yii\base\UserException;

/**
 * Class InvalidScopeException
 * @package app\modules\api\exceptions
 */
class InvalidScopeException extends UserException
{
	/**
	 * InvalidScopeException constructor.
	 * @param string $message
	 * @param int $code
	 * @param Throwable|null $previous
	 */
	public function __construct($message = "", $code = 0, Throwable $previous = null)
	{
		parent::__construct('The requested scope is invalid.', 0, $previous);

		$this->code = 'invalid_scope';
	}
}