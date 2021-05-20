<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\products\Products;
use app\models\products\ProductsSearch;
use pozitronik\core\traits\ControllerTrait;

/**
 * Class ProductsController
 * @package app\controllers
 */
class ProductsController extends DefaultController
{
	use ControllerTrait;

	/**
	 * Поисковая модель продуктов
	 * @var string
	 */
	public string $modelSearchClass = ProductsSearch::class;

	/**
	 * Модель продуктов
	 * @var string
	 */
	public string $modelClass = Products::class;

	/**
	 * Переопределим базовую директорию views
	 * @return string
	 */
	public function getViewPath(): string
	{
		return '@app/views/products';
	}
}