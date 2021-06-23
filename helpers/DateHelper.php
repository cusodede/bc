<?php
declare(strict_types = 1);

namespace app\helpers;

use DateTimeImmutable;
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

	/**
	 * @param int $timestamp
	 * @return DateTimeImmutable
	 */
	public static function createImmutableFromTimestamp(int $timestamp): DateTimeImmutable
	{
		return date_create_immutable(self::from_unix_timestamp($timestamp));
	}
}