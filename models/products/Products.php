<?php
declare(strict_types = 1);

namespace app\models\product;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\products\active_record\Products as ActiveRecordProduct;

/**
 * Функции продуктов, не относящиеся к ActiveRecord
 * Class Products
 * @package app\models\product
 */
class Products extends ActiveRecordProduct
{
	use ActiveRecordTrait;
}