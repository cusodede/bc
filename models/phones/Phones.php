<?php
declare(strict_types = 1);

namespace app\models\phones;

use app\components\db\ActiveRecordTrait;
use app\models\phones\active_record\PhonesAR;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * Class Phones
 * Вся логика работы с телефонными номерами
 */
class Phones extends PhonesAR
{
	use ActiveRecordTrait;

	/**
	 * @param string $phoneNum
	 * @return bool
	 */
	public static function isValidNumber(string $phoneNum): bool
	{
		try {
			$phoneNumber = PhoneNumberUtil::getInstance()->parse($phoneNum, 'RU', null, true);
			return null !== $phoneNumber && PhoneNumberUtil::getInstance()->isValidNumber($phoneNumber);
		} /** @noinspection BadExceptionsProcessingInspection */ catch (NumberParseException) {
			return false;
		}
	}

	/**
	 * @param string $phone
	 * @param bool $removePlus Удалит плюсы из номера.
	 * @return string|null
	 */
	public static function defaultFormat(string $phone, bool $removePlus = false): ?string
	{
		try {
			if (null !== $phoneNumber = PhoneNumberUtil::getInstance()->parse($phone, 'RU', null, true)) {
				$phoneNumber = PhoneNumberUtil::getInstance()->format($phoneNumber, PhoneNumberFormat::E164);
				return (true === $removePlus) ? str_replace('+', '',$phoneNumber) : $phoneNumber;
			}
		} /** @noinspection BadExceptionsProcessingInspection */ catch (NumberParseException) {
			return null;
		}
		return null;
	}

	/**
	 * @param string $phone
	 * @return string|null
	 * @noinspection BadExceptionsProcessingInspection
	 */
	public static function nationalFormat(string $phone): ?string
	{
		try {
			if (null !== $phoneNumber = PhoneNumberUtil::getInstance()->parse($phone, 'RU', null, true)) {
				return PhoneNumberUtil::getInstance()->getNationalSignificantNumber($phoneNumber);
			}
		} catch (NumberParseException) {
			return null;
		}
		return null;
	}

	/**
	 * Добавляет переданный список номеров, возвращая id записей массивом.
	 * Номера приводятся к единому виду.
	 * @param array $phones
	 * @return array
	 */
	public static function add(array $phones): array
	{
		$results = [];
		foreach (array_filter($phones) as $phone) {
			if (null !== $formattedNumber = static::defaultFormat($phone)) {
				$results[] = static::Upsert(['phone' => $formattedNumber])->id;
			}
		}
		return $results;
	}
}