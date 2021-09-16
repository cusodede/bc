<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var RevShareRatesSearch $searchModel
 * @var string $modelName
 * @var ControllerTrait $controller
 * @var ActiveDataProvider $dataProvider
 */

use app\components\helpers\Html;
use app\models\revshare_rates\RevShareRates;
use app\models\revshare_rates\EnumRevShareType;
use app\models\revshare_rates\RevShareRatesSearch;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use kartik\select2\Select2;
use pozitronik\grid_config\GridConfig;
use pozitronik\traits\traits\ControllerTrait;
use yii\data\ActiveDataProvider;
use yii\web\View;

$this->title = 'Ставки рефшеринга';
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
		'toolbar' => [
			['content' => Html::ajaxModalLink('Добавить ставку', $controller::to('create'), ['class' => ['btn btn-success']])]
		],
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => ActionColumn::class,
				'template' => '<div class="btn-group">{edit}{view}</div>',
				'buttons' => [
					'edit' => static function(string $url, RevShareRates $model) {
						return Html::ajaxModalLink('<i class="fas fa-edit"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
					'view' => static function(string $url, RevShareRates $model) {
						return Html::ajaxModalLink('<i class="fas fa-eye"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
				],
			],
			'id',
			[
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'type',
					'data' => EnumRevShareType::mapData(),
					'pluginOptions' => [
						'allowClear' => true,
						'placeholder' => ''
					]
				]),
				'attribute' => 'type',
				'format' => 'text',
				'value' => static fn(RevShareRates $item) => EnumRevShareType::getScalar($item->type),
			],
			'rate',
			'condition_value',
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
