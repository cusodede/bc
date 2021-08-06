<?php
declare(strict_types = 1);

namespace app\modules\api\exceptions;

use app\components\exceptions\ExtendedThrowable;
use Throwable;
use yii\base\UserException;

/**
 * Class InvalidGrantException
 * @package app\modules\api\exceptions
 */
class InvalidGrantException extends UserException implements ExtendedThrowable
{
	/**
	 * InvalidGrantException constructor.
	 * @param Throwable|null $previous
	 */
	public function __construct(Throwable $previous = null)
	{
		parent::__construct('Invalid or expired refresh token.', 400, $previous);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getErrorCode(): string
	{
		return 'ERR_GRANT';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUserFriendlyMessage(): string
	{
		return '';
	}
}