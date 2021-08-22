<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\abonents\AbonentsSearch;
use app\models\abonents\Abonents;
use app\models\products\Products;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class PartnersController
 * @package app\controllers
 */
class AbonentsController extends DefaultController
{

	/**
	 * @var string
	 */
	public string $modelSearchClass = AbonentsSearch::class;

	/**
	 * @var string
	 */
	public string $modelClass = Abonents::class;

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
	public function actionViewProducts($id): string
	{
		if (null === $model = $this->model::findOne($id)) {
			throw new NotFoundHttpException();
		}
		$query = Products::find()
			->where(['IN', 'id', ArrayHelper::getColumn($model->relatedAbonentsToProducts, 'product_id')]);

		$dataProvider = new ActiveDataProvider(['query' => $query]);
		$dataProvider->setSort([
			'attributes' => ['created_at', 'status_id']
		]);
		return $this->renderAjax('modal/view-products', [
			'dataProvider' => $dataProvider
		]);
	}
}
