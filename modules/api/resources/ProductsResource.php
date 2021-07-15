<?php
declare(strict_types = 1);

namespace app\modules\api\resources;

use app\models\abonents\Abonents;
use app\modules\api\resources\formatters\ProductFormatter;
use app\modules\api\resources\formatters\ProductFormatterInterface;
use Exception;
use yii\helpers\ArrayHelper;

/**
 * Class ProductsResource
 * @package app\modules\api\mybeeline\resources
 */
class ProductsResource
{
	/**
	 * @var ProductFormatterInterface|null
	 */
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
	 * @param Abonents $abonent
	 * @return array
	 */
	public function getFullProductList(Abonents $abonent): array
	{
		return array_map([$this->_formatter, 'format'], array_values($abonent->getFullProductList()));
	}

	/**
	 * @param Abonents $abonent
	 * @param int $productId
	 * @return array
	 * @throws Exception
	 */
	public function getSingleProduct(Abonents $abonent, int $productId): array
	{
		$products = $abonent->getFullProductList();
		if (null === $singleProduct = ArrayHelper::getValue($products, $productId)) {
			return [];
		}

		return $this->_formatter->format($singleProduct);
	}
}