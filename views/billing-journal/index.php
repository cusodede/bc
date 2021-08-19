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
use app\models\billing_journal\BillingJournal;
use app\models\billing_journal\EnumBillingJournalStatuses;
use app\models\products\Products;
use kartik\grid\GridView;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\View;

$this->title                   = 'История списаний';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= GridConfig::widget([
	'id' => "{$modelName}-index-grid",
	'grid' => GridView::begin([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [
			'heading' => '',
		],
		'showOnEmpty' => true,
		'striped' => false,
		'toolbar' => false,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			'id',
			[
				'attribute' => 'searchAbonentPhone',
				'label' => 'Телефон абонента',
				'content' => static function(BillingJournal $model) {
					return Html::ajaxModalLink(
						$model->relatedAbonent->phone,
						AbonentsController::to('view', ['id' => $model->relatedAbonent->id])
					);
				}
			],
			[
				'attribute' => 'searchProductId',
				'label' => 'Наименование продукта',
				'filter' => ArrayHelper::map(Products::find()->active()->all(), 'id', 'name'),
				'content' => static function(BillingJournal $model) {
					return Html::ajaxModalLink(
						$model->relatedProduct->name,
						ProductsController::to('view', ['id' => $model->relatedProduct->id])
					);
				}
			],
			'price',
			[
				'attribute' => 'status_id',
				'filter' => EnumBillingJournalStatuses::mapData(),
				'content' => static fn(BillingJournal $model) => EnumBillingJournalStatuses::getBadge($model->status_id)
			],
			[
				'attribute' => 'created_at',
				'format' => ['date', 'php:d.m.Y H:i'],
			],
		]
	])
]) ?>