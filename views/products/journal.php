<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $searchModel
 * @var string $modelName
 * @var ActiveDataProvider $dataProvider
 */

use app\components\helpers\Html;
use app\controllers\AbonentsController;
use app\controllers\ProductsController;
use app\models\products\EnumProductsStatuses;
use app\models\products\EnumProductsTypes;
use app\models\products\ProductsJournal;
use kartik\grid\GridView;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\View;
use app\models\products\Products;

$this->title = 'История подключений';

?>

<?= GridConfig::widget([
	'id'   => 'products-journal__index-grid',
	'grid' => GridView::begin([
		'dataProvider'     => $dataProvider,
		'filterModel'      => $searchModel,
		'panel'            => ['heading' => '',],
		'showOnEmpty'      => true,
		'toolbar'          => false,
		'export'           => false,
		'resizableColumns' => true,
		'responsive'       => true,
		'columns'          => [
			'id',
			[
				'attribute' => 'searchAbonentPhone',
				'label'     => 'Телефон абонента',
				'content'   => static function(ProductsJournal $model) {
					return Html::ajaxModalLink(
						$model->relatedAbonent->phone,
						AbonentsController::to('view', ['id' => $model->relatedAbonent->id])
					);
				}
			],
			[
				'attribute' => 'searchProductId',
				'label'     => 'Наименование продукта',
				'filter'    => ArrayHelper::map(Products::find()->active()->all(), 'id', 'name'),
				'content'   => static function(ProductsJournal $model) {
					return Html::ajaxModalLink(
						$model->relatedProduct->name,
						ProductsController::to('view', ['id' => $model->relatedProduct->id])
					);
				}
			],
			[
				'attribute' => 'searchProductTypeId',
				'label'     => 'Тип продукта',
				'filter'    => EnumProductsTypes::mapData(),
				'content'   => static fn(ProductsJournal $model) => $model->relatedProduct->typeDesc
			],
			[
				'attribute' => 'relatedProduct.payment_period',
				'label'     => 'Тип списания',
				'content'   => static fn(ProductsJournal $model) => $model->relatedProduct->paymentPeriodDesc
			],
			[
				'attribute' => 'status_id',
				'filter'    => EnumProductsStatuses::mapData(),
				'content'   => static function(ProductsJournal $model) {
					switch ($model->status_id) {
						case EnumProductsStatuses::STATUS_ENABLED:
							return Html::tag('span', $model->statusDesc, ['class' => ['badge border border-success text-success']]);
						case EnumProductsStatuses::STATUS_RENEWED:
							return Html::tag('span', $model->statusDesc, ['class' => ['badge border border-info text-info']]);
						case EnumProductsStatuses::STATUS_DISABLED:
							return Html::tag('span', $model->statusDesc, ['class' => ['badge border border-danger text-danger']]);
						default:
							return $model->statusDesc;
					}
				}
			],
			[
				'attribute' => 'expire_date',
				'format'    => ['date', 'php:d.m.Y H:i'],
			],
			[
				'attribute' => 'created_at',
				'format'    => ['date', 'php:d.m.Y H:i'],
			],
		],
	])
]) ?>