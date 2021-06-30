<?php
declare(strict_types = 1);

namespace app\modules\api\resources\formatters;

use app\models\products\Products;

/**
 * Interface ProductFormatterInterface
 * @package app\modules\api\resources\formatters
 */
interface ProductFormatterInterface {
	/**
	 * @param Products $product
	 * @return array
	 */
	public function format(Products $product):array;
}