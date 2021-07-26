<?php
declare(strict_types = 1);

namespace app\modules\api\error;

use app\modules\api\exceptions\ApiExceptionInterface;
use app\modules\api\exceptions\ValidationException;
use Error;
use Exception;
use yii\base\UserException;
use yii\web\ErrorHandler as YiiErrorHandler;
use yii\web\HttpException;

/**
 * Class ErrorHandler
 * @package app\modules\api\error
 */
class ErrorHandler extends YiiErrorHandler
{
	/**
	 * @param Error|Exception $exception
	 * @return array
	 */
	protected function convertExceptionToArray($exception): array
	{
		if (!YII_DEBUG && !$exception instanceof UserException && !$exception instanceof HttpException) {
			$exception = new HttpException(500, 'An internal server error occurred.');
		}

		if ($exception instanceof ValidationException) {
			$result['error']['code'] = $exception->getErrorCode();
			$result['error']['desc'] = $exception->getErrors();
		} else {
			if ($exception instanceof ApiExceptionInterface) {
				$result['error']['code'] = $exception->getErrorCode();
			} elseif ($exception instanceof HttpException) {
				$result['error']['code'] = $exception->statusCode;
			} else {
				$result['error']['code'] = $exception->getCode();
			}

			$result['error']['desc'] = $exception->getMessage();
		}

		if (YII_DEBUG) {
			$result['debug']['trace'] = $exception->getTraceAsString();
		}

		return $result;
	}
}