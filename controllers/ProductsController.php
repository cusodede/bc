<?php
declare(strict_types = 1);

namespace app\controllers;


use app\models\products\Products;
use pozitronik\helpers\ReflectionHelper;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

/**
 * Управление товарами (админка)
 */
class ProductsController extends Controller {

	/**
	 * @return string
	 */
	public function actionIndex() {

		return $this->render('index', [
				'dataProvider' => new ArrayDataProvider([
					'allModels' => Products::all()
				]),
				'controller' => $this,
				'modelName' => ReflectionHelper::GetClassShortName(Products::class)
			]
		);
	}

}