<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\abonents\AbonentsSearch;
use app\models\abonents\Abonents;
use app\models\products\Products;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
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
	 * @throws NotFoundHttpException|InvalidConfigException
	 */
	public function actionViewProducts(int $id): string
	{
		$model = $this->model::findOne($id);
		if ($model === null) {
			throw new NotFoundHttpException();
		}
		$query = Products::find()
			->where(['IN', 'id', ArrayHelper::getColumn(
				$model->relatedAbonentsToProducts, 'product_id'
			)]);

		$dataProvider = new ActiveDataProvider(['query' => $query]);
		$dataProvider->setSort([
			'attributes' => ['created_at', 'status_id']
		]);
		return $this->renderAjax('modal/view-products', [
			'dataProvider' => $dataProvider
		]);
	}
}
