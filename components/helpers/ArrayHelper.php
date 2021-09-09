<?php
declare(strict_types = 1);

namespace app\components\helpers;

use pozitronik\helpers\ArrayHelper as VendorArrayHelper;

/**
 * Class ArrayHelper
 * @package app\components\helpers
 */
class ArrayHelper extends VendorArrayHelper
{
	/**
	 * @param array $array
	 * @return mixed
	 */
	public static function getRandomItem(array $array): mixed
	{
		/** @var int|string $rand */
		$rand = array_rand($array);
		return $array[$rand];
	}

	/**
	 * Переименование ключей в массиве.
	 * Например, переименовать ошибки полей в доле на наши.
	 * $map = [входное поле => выходное поле]
	 *
	 * @param array $array
	 * @param array $map
	 * @return array
	 */
	public static function renameKeysByMap(array $array, array $map): array
	{
		$result = [];

		foreach ($array as $key => $value) {
			if (array_key_exists($key, $map)) {
				$newNameKey = $map[$key];
				$result[$newNameKey] = $value;
			} else {
				$result[$key] = $value;
			}
		}

		return $result;
	}
}