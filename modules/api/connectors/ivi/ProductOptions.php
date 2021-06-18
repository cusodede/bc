<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\ivi;

/**
 * Класс для инкапсуляции параметров, используемых для взаимодействия с API ivi.
 * Class ProductOptions
 * @package app\modules\api\connectors\ivi
 */
class ProductOptions
{
	/**
	 * @var string|null идентификатор продукта, выдываемый партнером ivi.
	 */
	private ?string $_product;
	/**
	 * @var string|null идентификатор версии приложения, выдываемый партнером ivi (уникальное значение для каждого продукта).
	 */
	private ?string $_appVersion;
	/**
	 * @var string|null телефон абонента.
	 */
	private ?string $_abonentPhone;
	/**
	 * @var string|null уникальный параметр транзакции при подключении опции в ivi (например, идентификатор из биллинга).
	 */
	private ?string $_transactionId;

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

	public function getTransactionId(): ?string
	{
		return $this->_transactionId;
	}
}