<?php
declare(strict_types = 1);

namespace app\modules\api\exceptions;

/**
 * Interface ApiExceptionInterface
 * @package app\modules\api\exceptions
 */
interface ApiExceptionInterface
{
	/**
	 * Custom error code.
	 * @return string
	 */
	public function getErrorCode(): string;
}