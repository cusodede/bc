<?php
declare(strict_types = 1);

namespace app\components\exceptions;

use Throwable;

/**
 * Interface ExtendedThrowable
 * @package app\modules\api\exceptions
 */
interface ExtendedThrowable extends Throwable
{
	/**
	 * Custom error code.
	 * @return string
	 */
	public function getErrorCode(): string;

	/**
	 * @return string
	 */
	public function getUserFriendlyMessage(): string;
}