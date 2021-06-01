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
	 * @throws NumberParseException
	 */
	public static function isValidNumber(string $phoneNum):bool {
		$phoneNumberUtil = PhoneNumberUtil::getInstance();
		$phoneNumber = $phoneNumberUtil->parse($phoneNum, 'RU', null, true);
		return null === $phoneNumber?false:$phoneNumberUtil->isValidNumber($phoneNumber);
	}

	/**
	 * Добавляет переданный список номеров, возвращая id записей массивом.
	 * Номера приводятся к единому виду.
	 * @param array $phones
	 * @return array
	 * @throws NumberParseException
	 */
	public static function add(array $phones):array {
		$results = [];
		$phoneNumberUtil = PhoneNumberUtil::getInstance();
		foreach ($phones as $phone) {
			if (null !== $phoneNumber = $phoneNumberUtil->parse($phone, 'RU', null, true)) {
				$formattedNumber = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::E164);
				$results[] = self::Upsert(['phone' => $formattedNumber])->id;
			}

		}
		return $results;

	}

}