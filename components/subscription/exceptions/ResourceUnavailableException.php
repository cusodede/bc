<?php
declare(strict_types = 1);

namespace app\components\subscription\exceptions;

use app\components\exceptions\ExtendedThrowable;
use Exception;
use Throwable;

/**
 * TBD
 */
class ResourceUnavailableException extends Exception implements ExtendedThrowable
{
	/**
	 * @param string $message
	 * @param Throwable|null $previous
	 */
	public function __construct($message = "", Throwable $previous = null)
	{
		parent::__construct($message, 503, $previous);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getErrorCode(): string
	{
		return 'ERR_RESOURCE_UNAVAILABLE';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUserFriendlyMessage(): string
	{
		return 'Наблюдаются технические неполадки на стороне партнера';
	}
}