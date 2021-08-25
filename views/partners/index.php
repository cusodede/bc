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
use app\models\common\RefPartnersCategories;

$this->title                   = 'Партнеры';
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
			['content' => Html::ajaxModalLink('Добавить партнера', $controller::to('create'), ['class' => ['btn btn-success']])]
		],
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => ActionColumn::class,
				'template' => '<div class="btn-group">{edit}{view}</div>',
				'buttons' => [
					'edit' => static function(string $url, Model $model) {
						return Html::ajaxModalLink('<i class="fas fa-edit"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
					'view' => static function(string $url, Model $model) {
						return Html::ajaxModalLink('<i class="fas fa-eye"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
				],
			],
			'id',
			'name',
			'inn',
			[
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'category_id',
					'data' => RefPartnersCategories::mapData(),
					'pluginOptions' => [
						'allowClear' => true,
						'placeholder' => ''
					]
				]),
				'attribute' => 'category_id',
				'format' => 'text',
				'value' => 'relatedCategory.name',
			],
			'phone',
			'email',
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