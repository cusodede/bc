<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var AbonentsSearch $searchModel
 * @var string $modelName
 * @var ControllerTrait $controller
 * @var ActiveDataProvider $dataProvider
 */

use app\components\helpers\Html;
use app\models\abonents\Abonents;
use app\models\abonents\AbonentsSearch;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use pozitronik\grid_config\GridConfig;
use pozitronik\traits\traits\ControllerTrait;
use yii\data\ActiveDataProvider;
use yii\web\View;

$this->title                   = 'Абоненты';
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
			['content' => Html::ajaxModalLink('Добавить абонента', $controller::to('create'), ['class' => ['btn btn-success']])]
		],
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class'    => ActionColumn::class,
				'template' => '<div class="btn-group">{edit}{view}{view-products}</div>',
				'buttons'  => [
					'edit' => static function(string $url, Abonents $model) {
						return Html::ajaxModalLink('<i class="fas fa-edit"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
					'view' => static function(string $url, Abonents $model) {
						return Html::ajaxModalLink('<i class="fas fa-eye"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
					'view-products' => static function(string $url, Abonents $model) {
						return Html::ajaxModalLink('<i class="fas fa-arrow-circle-up"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
				],
			],
			'id',
			'surname',
			'name',
			'patronymic',
			'phone',
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