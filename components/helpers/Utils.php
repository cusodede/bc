<?php
declare(strict_types = 1);

namespace app\components\helpers;

use Throwable;

/**
 * Хелпер для всяких замысловатых штук.
 */
class Utils
{
	/**
	 * @param string $url
	 * @return bool
	 */
	public static function doUrlHealthcheck(string $url): bool
	{
		try {
			$headers = get_headers($url);
			return in_array('HTTP/1.1 200 OK', $headers, true);
		} /** @noinspection BadExceptionsProcessingInspection */ catch (Throwable $e) {
			return false;
		}
	}
}