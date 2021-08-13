<?php
declare(strict_types = 1);

namespace app\components\helpers;

use pozitronik\helpers\Utils as VendorUtils;
use Throwable;

/**
 * Хелпер для всяких замысловатых штук.
 */
class Utils extends VendorUtils
{
	/**
	 * @param string $url
	 * @return bool
	 */
	public static function doUrlHealthCheck(string $url): bool
	{
		try {
			$headers = get_headers($url);
			return in_array('HTTP/1.1 200 OK', $headers, true);
		} /** @noinspection BadExceptionsProcessingInspection */ catch (Throwable) {
			return false;
		}
	}
}