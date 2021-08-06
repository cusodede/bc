<?php
declare(strict_types = 1);

namespace app\modules\api\exceptions;

use app\components\exceptions\ExtendedThrowable;
use Throwable;
use yii\base\UserException;

/**
 * Class InvalidScopeException
 * @package app\modules\api\exceptions
 */
class InvalidScopeException extends UserException implements ExtendedThrowable
{
	/**
	 * InvalidScopeException constructor.
	 * @param Throwable|null $previous
	 */
	public function __construct(Throwable $previous = null)
	{
		parent::__construct('The requested scope is invalid.', 400, $previous);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getErrorCode(): string
	{
		return 'ERR_SCOPE';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUserFriendlyMessage(): string
	{
		return '';
	}
}