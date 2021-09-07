<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\abonents\AbonentsSearch;
use app\models\abonents\Abonents;
use app\models\products\ProductsSearch;
use Yii;
use yii\web\NotFoundHttpException;

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
	 * @param int $id
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionViewProducts(int $id): string
	{
		if (null === $model = Abonents::findOne($id)) {
			throw new NotFoundHttpException();
		}

		$searchModel = new ProductsSearch();
		$dataProvider = $searchModel->search([$searchModel->formName() => ['abonent_id' => $id]]);

		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('modal/view-products', [
				'dataProvider' => $dataProvider,
				'phone' => $model->phone,
			]);
		}
		return $this->render('view-products', [
			'dataProvider' => $dataProvider,
			'phone' => $model->phone,
		]);


	}
}
