<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\ivi;

/**
 * Class ProductOptions
 * @package app\modules\api\connectors\ivi
 */
class ProductOptions
{
	private ?string $_product;
	private ?string $_appVersion;
	private ?string $_abonentPhone;

	public function getProduct(): ?string
	{
		return $this->_product;
	}

	public function getAppVersion(): ?string
	{
		return $this->_appVersion;
	}

	public function getAbonentPhone(): ?string
	{
		return $this->_abonentPhone;
	}
}