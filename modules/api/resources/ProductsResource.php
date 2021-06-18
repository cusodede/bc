<?php
declare(strict_types = 1);

namespace app\modules\api\resources;

use app\models\abonents\Abonents;
use app\models\products\Products;
use app\modules\api\resources\formatters\ProductFormatter;
use app\modules\api\resources\formatters\ProductFormatterInterface;
use pozitronik\helpers\ArrayHelper;

/**
 * Class ProductsResource
 * @package app\modules\api\mybeeline\resources
 */
class ProductsResource
{
	/**
	 * @var ProductFormatterInterface|null
	 */
	private ?ProductFormatterInterface $_formatter;

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
	public function getAbonentProducts(Abonents $abonent): array
	{
		$products = array_merge(
			$abonent->getExistentProducts(),
			Products::find()
				->where(['NOT IN', 'id', ArrayHelper::getColumn($abonent->relatedAbonentsToProducts, 'product_id')])
				->all()
		);

		return array_map([$this->_formatter, 'format'], $products);
	}
}