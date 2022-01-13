<?php
declare(strict_types = 1);

namespace app\models\phones;

use app\components\db\ActiveRecordTrait;
use app\models\phones\active_record\PhonesAR;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Throwable;

/**
 * Class Phones
 * Вся логика работы с телефонными номерами
 *
 * @property bool $isConfirmed Подтверждён ли телефонный номер
 * todo: спор с artemvolt по поводу организации доступа к данным:
 * @see https://github.com/cusodede/dpl/pull/88#issuecomment-882357793 и далее.
 * Под давлением сроков оставляем, как есть, вернёмся по возможности.
 *
 */
class Phones extends PhonesAR {
	use ActiveRecordTrait;

	public const STATUS_NOT_CONFIRM = 1;
	public const STATUS_CONFIRM = 2;

	public static $mapStatusNames = [
		self::STATUS_CONFIRM => 'Подтвержден',
		self::STATUS_NOT_CONFIRM => 'Не подтвержден'
	];

	/**
	 * @return string
	 */
	public function getStatusName():string {
		return self::$mapStatusNames[$this->status]??"Не установлен";
	}

	/**
	 * @param string $phoneNum
	 * @param bool $checkRawFormat true: проверить, является ли $phoneNum непосредственно корректно форматированным
	 * телефонным номером, false: содержит ли $phoneNum запись корректного телефонного номера.
	 * Пример:
	 * +7 925 528 00 00 - корректная запись, но некорректный формат,
	 * 89255280000 - корректно форматированный номер.
	 *
	 * @return bool
	 * @throws Throwable
	 */
	public static function isValidNumber(string $phoneNum, bool $checkRawFormat = true):bool {
		try {
			$phoneUtil = PhoneNumberUtil::getInstance();
			if (null === $phoneNumber = $phoneUtil->parse($phoneNum, 'RU', null, true)) return false;
			if (null === $formattedNumber = self::defaultFormat($phoneNum)) return false;

			return $phoneUtil->isValidNumber($phoneNumber) && (!$checkRawFormat || $phoneNumber->getRawInput() === $formattedNumber);
		} catch (NumberParseException) {
			return false;
		}
	}

	/**
	 * @param string $phone
	 * @return string|null
	 */
	public static function defaultFormat(string $phone):?string {
		try {
			if (null !== $phoneNumber = PhoneNumberUtil::getInstance()->parse($phone, 'RU', null, true)) {
				return PhoneNumberUtil::getInstance()->format($phoneNumber, PhoneNumberFormat::E164);
			}
		} catch (NumberParseException) {
			return null;
		}
		return null;
	}

	/**
	 * @param string $phone
	 * @return string|null
	 */
	public static function nationalFormat(string $phone):?string {
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
	public static function add(array $phones):array {
		$results = [];
		foreach (array_filter($phones) as $phone) {
			if (null !== $formattedNumber = self::defaultFormat($phone)) {
				$results[] = self::Upsert(['phone' => $formattedNumber])->id;
			}
		}
		return $results;

	}

	/**
	 * @return bool
	 */
	public function getIsConfirmed():bool {
		return $this->status === self::STATUS_CONFIRM;
	}

	/**
	 * @param bool $isConfirmed
	 */
	public function setIsConfirmed(bool $isConfirmed = true):void {
		$this->status = $isConfirmed?self::STATUS_CONFIRM:self::STATUS_NOT_CONFIRM;
	}

	/**
	 * @param string $phone_number
	 * @return bool
	 */
	public function isEqualNumber(string $phone_number):bool {
		$format = self::defaultFormat($phone_number);
		return $format === $this->phone;
	}

	/**
	 * ДОЛ принимает телефон только в определенном формате (10 знаков).
	 * Phones::nationalFormat() не дает нужного эффекта.
	 * А вообще, там какая-то странная фигня происходит, которая не совсем понятна. Чтобы хоть что-то получить сделал так.
	 * @param string $phone
	 * @return string
	 */
	public static function dolPhoneFormat(string $phone):string {
		return static::nationalFormat((11 === strlen($phone))?mb_substr($phone, 1):$phone);
	}
}
