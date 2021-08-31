<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\abonents\AbonentsSearch;
use app\models\abonents\Abonents;
use Yii;

/**
 * Class PartnersController
 * @package app\controllers
 */
class AbonentsController extends DefaultController
{
	public string $modelSearchClass = AbonentsSearch::class;
	public string $modelClass = Abonents::class;

	/**
	 * @inheritDoc
	 */
	public function getViewPath(): string
	{
		return '@app/views/abonents';
	}

	/**
	 * Показать все продукты абонента.
	 * @noinspection PhpPossiblePolymorphicInvocationInspection
	 */
	public function actionViewProducts(): string
	{
		$params      = Yii::$app->request->queryParams;
		$searchModel = $this->searchModel;
		['dataProvider' => $dataProvider, 'model' => $model] = $searchModel->searchProductsToAbonent($params);
		return $this->renderAjax('modal/view-products', [
			'dataProvider' => $dataProvider,
			'phone' => $model['phone'],
		]);
	}
}
