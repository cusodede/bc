<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $searchModel
 * @var string $modelName
 * @var ControllerTrait $controller
 * @var ActiveDataProvider $dataProvider
 */

use app\components\helpers\Html;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use pozitronik\grid_config\GridConfig;
use pozitronik\traits\traits\ControllerTrait;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\select2\Select2;
use app\models\partners\Partners;
use pozitronik\helpers\ArrayHelper;
use app\models\products\EnumProductsTypes;
use app\models\products\Products;

$this->title                   = 'Продукты';
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
		'toolbar' => false,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => ActionColumn::class,
				'template' => '<div class="btn-group">{view-abonents}</div>',
				'buttons' => [
					'view-abonents' => static function(string $url, Model $model) {
						return Html::ajaxModalLink('<i class="fas fa-arrow-circle-up"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
				],
			],
			'id',
			'name',
			'price',
			[
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'type_id',
					'data' => EnumProductsTypes::mapData(),
					'pluginOptions' => [
						'allowClear' => true,
						'placeholder' => ''
					]
				]),
				'attribute' => 'type_id',
				'format' => 'text',
				'value' => static fn(Products $product) => EnumProductsTypes::getScalar($product->type_id),
			],
			[
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'partner_id',
					'data' => ArrayHelper::map(Partners::find()->active()->all(), 'id', 'name'),
					'pluginOptions' => [
						'allowClear' => true,
						'placeholder' => ''
					]
				]),
				'attribute' => 'partner_id',
				'format' => 'text',
				'value' => 'relatedPartner.name',
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'created_at',
				'format' => ['date', 'php:d.m.Y H:i'],
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'updated_at',
				'format' => ['date', 'php:d.m.Y H:i'],
			],
		],
	])
]) ?>