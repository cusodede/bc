<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\product\Product;
use app\models\product\ProductSearch;
use pozitronik\core\traits\ControllerTrait;

/**
 * Class MerchController
 * Управление товарами (админка)
 */
class ProductController extends DefaultController {
	use ControllerTrait;

	public string $modelClass = Product::class;
	public string $modelSearchClass = ProductSearch::class;

}