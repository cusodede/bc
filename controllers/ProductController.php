<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\product\Product;
use app\models\product\ProductSearch;
use app\models\sys\permissions\filters\PermissionFilter;
use yii\helpers\ArrayHelper;

/**
 * Управление товарами (админка)
 */
class ProductController extends DefaultController {

	public string $modelClass = Product::class;
	public string $modelSearchClass = ProductSearch::class;

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return ArrayHelper::merge(parent::behaviors(), [
			'access' => [
				'class' => PermissionFilter::class
			]
		]);
	}
}