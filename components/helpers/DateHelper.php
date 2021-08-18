<?php
declare(strict_types = 1);

namespace app\components\helpers;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use pozitronik\helpers\DateHelper as VendorDateHelper;

/**
 * Class DateHelper
 * @package app\helpers
 */
class DateHelper extends VendorDateHelper
{
	/**
	 * @param DateTimeInterface|int|string $date
	 * @return string дата в формате ISO8601.
	 * @see https://www.php.net/manual/en/datetime.format.php
	 */
	public static function toIso8601(DateTimeInterface|int|string $date): string
	{
		if (is_numeric($date)) {
			return date(DateTime::ATOM, $date);
		}
		if ($date instanceof DateTimeInterface) {
			return $date->format(DateTime::ATOM);
		}

		return date_create($date)->format(DateTime::ATOM);
	}

	/**
	 * @param int $timestamp
	 * @return DateTimeImmutable
	 */
	public static function createImmutableFromTimestamp(int $timestamp): DateTimeImmutable
	{
		return date_create_immutable(self::from_unix_timestamp($timestamp));
	}

	/**
	 * @param string $date
	 * @param string $format
	 * @return string
	 */
	public static function toFormat(string $date, string $format = 'Y-m-d H:i:s'): string
	{
		return date_create($date)->format($format);
	}
}