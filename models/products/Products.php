<?php
declare(strict_types = 1);

namespace app\models\products;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\products\active_record\Products as ActiveRecordProducts;

/**
 * Логика продуктов, не относящиеся к ActiveRecord
 * Class Products
 * @package app\models\product
 */
class Products extends ActiveRecordProducts
{
	use ActiveRecordTrait;
}