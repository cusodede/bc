<?php
declare(strict_types = 1);

namespace app\models\addresses;

use app\models\addresses\active_record\AddressesAR;

/**
 * Class Addresses
 * @package app\models\addresses
 *
 * @property string $regAddressString Полный адрес
 */
class Addresses extends AddressesAR {
	/**
	 * @return string
	 */
	public function getRegAddressString():string {
		return trim(
			implode(
				', ',
				array_filter([$this->index, $this->area, $this->region, $this->city, $this->street, $this->building])
			)
		);
	}
}