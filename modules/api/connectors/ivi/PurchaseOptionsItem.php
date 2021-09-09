<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\ivi;

use Exception;
use yii\helpers\ArrayHelper;

/**
 * Class PurchaseOptionsItem
 * @package app\modules\api\connectors\ivi
 */
class PurchaseOptionsItem
{
	private array $_data;

	/**
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$this->_data = $data;
	}

	/**
	 * Получение идентификатора продукта в рамках услуги.
	 * @return string|null
	 * @throws Exception
	 */
	public function getProductId(): ?string
	{
		return ArrayHelper::getValue($this->_data, 'product_identifier');
	}

	/**
	 * Получение списка параметров, принадлежаших услуге.
	 * @return array
	 * @throws Exception
	 */
	public function getPurchaseParams(): array
	{
		$params = ArrayHelper::getValue($this->_data, 'payment_options.0.purchase_params', []);

		return array_filter($params, static fn(string $name) => '_' === $name[0], ARRAY_FILTER_USE_KEY);
	}

	/**
	 * Получение подписи, идентифицирующей список параметров услуги.
	 * @return string|null
	 * @throws Exception
	 */
	public function getSignParam(): ?string
	{
		return ArrayHelper::getValue($this->_data, 'payment_options.0.purchase_params.sign');
	}
}