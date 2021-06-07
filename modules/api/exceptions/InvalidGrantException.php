<?php
declare(strict_types = 1);

namespace app\modules\api\exceptions;

use Throwable;
use yii\base\UserException;

/**
 * Class InvalidGrantException
 * @package app\modules\api\exceptions
 */
class InvalidGrantException extends UserException
{
	/**
	 * InvalidGrantException constructor.
	 * @param string $message
	 * @param int $code
	 * @param Throwable|null $previous
	 */
	public function __construct($message = "", $code = 0, Throwable $previous = null)
	{
		parent::__construct('Invalid or expired refresh token.', 0, $previous);

		$this->code = 'invalid_grant';
	}
}