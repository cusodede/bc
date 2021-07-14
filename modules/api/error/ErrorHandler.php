<?php
declare(strict_types = 1);

namespace app\modules\api\error;

use app\modules\api\exceptions\ValidationException;
use Error;
use Exception;
use yii\base\UserException;
use yii\web\HttpException;

/**
 * Class ErrorHandler
 * @package app\modules\api\error
 */
class ErrorHandler extends \yii\web\ErrorHandler
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

		$result = [
			'err' => $exception instanceof HttpException ? $exception->statusCode : $exception->getCode(),
			'msg' => $exception->getMessage()
		];

		if ($exception instanceof ValidationException) {
			$result['msg'] = $exception->getErrors();
		}

		if (YII_DEBUG) {
			$result['debug']['trace'] = $exception->getTraceAsString();
		}

		return $result;
	}
}