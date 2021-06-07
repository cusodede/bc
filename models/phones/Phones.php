<?php
declare(strict_types = 1);

namespace app\models\phones;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\phones\active_record\PhonesAR;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * Class Phones
 * Вся логика работы с телефонными номерами
 */
class Phones extends PhonesAR {
	use ActiveRecordTrait;

	/**
	 * @param string $phoneNum
	 * @return bool
	 */
	public static function isValidNumber(string $phoneNum):bool {
		try {
			$phoneNumber = PhoneNumberUtil::getInstance()->parse($phoneNum, 'RU', null, true);
			return !(null === $phoneNumber) && PhoneNumberUtil::getInstance()->isValidNumber($phoneNumber);
		} /** @noinspection BadExceptionsProcessingInspection */ catch (NumberParseException $exception) {
			return false;
		}

	}

	public static function defaultFormat(string $phone):?string {
		if (null !== $phoneNumber = PhoneNumberUtil::getInstance()->parse($phone, 'RU', null, true)) {
			return PhoneNumberUtil::getInstance()->format($phoneNumber, PhoneNumberFormat::E164);
		}
		return null;
	}

	/**
	 * Добавляет переданный список номеров, возвращая id записей массивом.
	 * Номера приводятся к единому виду.
	 * @param array $phones
	 * @return array
	 */
	public static function add(array $phones):array {
		$results = [];
		foreach ($phones as $phone) {
			if (null !== $formattedNumber = self::defaultFormat($phone)) {
				$results[] = self::Upsert(['phone' => $formattedNumber])->id;
			}
		}
		return $results;

	}

}