<?php
declare(strict_types = 1);

namespace app\modules\api\resources;

use app\models\abonents\Abonents;
use app\models\products\Products;
use app\modules\api\resources\formatters\ProductFormatter;
use app\modules\api\resources\formatters\ProductFormatterInterface;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

/**
 * Class ProductsResource
 * @package app\modules\api\mybeeline\resources
 */
class ProductsResource
{
	private ProductFormatterInterface $_formatter;

	/**
	 * ProductsResource constructor.
	 * @param ProductFormatterInterface|null $formatter
	 */
	public function __construct(?ProductFormatterInterface $formatter = null)
	{
		$this->_formatter = $formatter ?? new ProductFormatter();
	}

	/**
	 * Получением списка продуктов по абоненту.
	 * @param string $phone
	 * @return array
	 * @throws InvalidConfigException
	 */
	public function getFullProductList(string $phone): array
	{
		return array_map([$this->_formatter, 'format'], array_values($this->getProducts($phone)));
	}

	/**
	 * @param string $phone
	 * @param int $productId
	 * @return array
	 * @throws InvalidConfigException
	 */
	public function getSingleProduct(string $phone, int $productId): array
	{
		if (null === $singleProduct = ArrayHelper::getValue($this->getProducts($phone), $productId)) {
			return [];
		}

		return $this->_formatter->format($singleProduct);
	}

	/**
	 * @param string $phone
	 * @return Products[]
	 * @throws InvalidConfigException
	 */
	private function getProducts(string $phone): array
	{
		if (null === $abonent = Abonents::findByPhone($phone)) {
			$products = Products::find()
				->whereActivePeriod()
				->indexBy('id')
				->all();
		} else {
			$products = $abonent->getFullProductList();
		}

		return $products;
	}
}