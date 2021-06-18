<?php
declare(strict_types = 1);

namespace app\helpers;

use pozitronik\helpers\DateHelper as VendorDateHelper;

/**
 * Class DateHelper
 * @package app\helpers
 */
class DateHelper extends VendorDateHelper
{
	/**
	 * @param string $date
	 * @return string дата в формате ISO8601.
	 * @see https://www.php.net/manual/en/datetime.format.php
	 */
	public static function toIso8601(string $date): string
	{
		return date_create($date)->format('c');
	}
}