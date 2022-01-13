<?php
declare(strict_types = 1);

namespace app\components\helpers;

use pozitronik\helpers\ArrayHelper as VendorArrayHelper;

/**
 * Class ArrayHelper
 * @package app\components\helpers
 */
class ArrayHelper extends VendorArrayHelper {
	/**
	 * @param array $array
	 * @return mixed
	 */
	public static function getRandItem(array $array) {
		/** @var int|string $rand */
		$rand = array_rand($array);
		return $array[$rand];
	}

	/**
	 * Переименовать в ключах массива $array все вхождения строки $search на $replace
	 *
	 * @param array $array
	 * @param string $search
	 * @param string $replace
	 * @return array
	 */
	public static function replaceStrInKeys(array $array, string $search, string $replace):array {
		return array_combine(str_replace($search, $replace, array_keys($array)), $array);
	}
}
