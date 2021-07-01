<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\products\Products;
use app\models\products\ProductsJournalSearch;
use app\models\products\ProductsSearch;
use Yii;

/**
 * Class ProductsController
 * @package app\controllers
 */
class ProductsController extends DefaultController
{
	/**
	 * Модель продуктов
	 * @var string
	 */
	public string $modelClass = Products::class;

	/**
	 * Поисковая модель продуктов
	 * @var string
	 */
	public string $modelSearchClass = ProductsSearch::class;

	/**
	 * @return string
	 */
	public function actionJournal(): string
	{
		$searchModel = new ProductsJournalSearch();

		return $this->render('journal', [
			'searchModel'  => $searchModel,
			'dataProvider' => $searchModel->search(Yii::$app->request->queryParams)
		]);
	}

	/**
	 * Переопределим базовую директорию views
	 * @return string
	 */
	public function getViewPath(): string
	{
		return '@app/views/products';
	}
}