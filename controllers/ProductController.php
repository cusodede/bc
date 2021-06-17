<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\product\Product;
use app\models\product\ProductSearch;

/**
 * Управление товарами (админка)
 */
class ProductController extends DefaultController {

	public string $modelClass = Product::class;
	public string $modelSearchClass = ProductSearch::class;

}