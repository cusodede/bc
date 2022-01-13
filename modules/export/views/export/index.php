<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var SysExportSearch $searchModel
 * @var string $modelName
 * @var ControllerTrait $controller
 * @var ActiveDataProvider $dataProvider
 */

use app\assets\GridHelperAsset;
use app\assets\ModalHelperAsset;
use app\components\grid\ActionColumn;
use app\components\grid\widgets\toolbar_filter_widget\ToolbarFilterWidget;
use app\components\helpers\ArrayHelper;
use app\components\helpers\Html;
use app\controllers\UsersController;
use app\modules\export\models\SysExport;
use app\modules\export\models\SysExportSearch;
use kartik\grid\GridView;
use pozitronik\filestorage\FSModule;
use pozitronik\filestorage\models\FileStorage;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\Utils;
use pozitronik\traits\traits\ControllerTrait;
use pozitronik\widgets\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\grid\DataColumn;
use yii\web\JsExpression;
use yii\web\View;

ModalHelperAsset::register($this);
GridHelperAsset::register($this);

$id = "{$modelName}-index-grid";
?>
<?= GridConfig::widget([
	'id' => $id,
	'grid' => GridView::begin([
		'id' => $id,
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'filterOnFocusOut' => false,
		'panel' => [
			'heading' => false,
		],
		'replaceTags' => [
			'{optionsBtn}' => ToolbarFilterWidget::widget(['content' => '{options}']),
			'{totalCount}' => ($dataProvider->totalCount > 0)?Utils::pluralForm($dataProvider->totalCount, ['запись', 'записи', 'записей']):"Нет записей",
			'{newRecord}' => ToolbarFilterWidget::widget([
				'label' => ($dataProvider->totalCount > 0)?Utils::pluralForm($dataProvider->totalCount, ['запись', 'записи', 'записей']):"Нет записей",
				'content' => Html::link('Новая запись', $controller::to('create'), ['class' => 'btn btn-success'])
			]),
			'{filterBtn}' => ToolbarFilterWidget::widget([
				'content' => Html::button(
					"<i class='fa fa-filter'></i>",
					['onclick' => new JsExpression('setFakeGridFilter("#'.$id.'")'), 'class' => 'btn btn-info']
				)
			]),
		],
		'toolbar' => [
			'{filterBtn}'
		],
		'panelBeforeTemplate' => '{optionsBtn}{newRecord}{toolbarContainer}{before}<div class="clearfix"></div>',
		'summary' => null,
		'showOnEmpty' => true,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => ActionColumn::class,
				'template' => '<div class="btn-group">{view}</div>',
			],
			'id',
			'updated_at',
			'created_at',
			[
				'class' => DataColumn::class,
				'attribute' => 'file',
				'value' => static function(SysExportSearch $model) {
					/** @var FileStorage $fileStorage */
					if (null === $fileStorage = FileStorage::find()->where(['model_key' => $model->id, 'model_name' => SysExport::class])->one()) return null;
					return (null === $fileStorage->size)
						?Html::tag('i', '', ['class' => 'fa fa-exclamation-triangle', 'style' => 'color: red']).$fileStorage->name //файл не найден
						:FSModule::a($fileStorage->name, ['index/download', 'id' => $fileStorage->id]);
				},
				'format' => 'raw'
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'storage',
				'value' => static fn(SysExportSearch $model) => BadgeWidget::widget([
					'items' => $model->relatedStorage
						?Html::a($model->relatedStorage->filename, ['/s3/download', 'id' => $model->relatedStorage->id])
						:null
				]),
				'format' => 'raw'
			],
			'extra_data',
			[
				'class' => DataColumn::class,
				'attribute' => 'status',
				'value' => static fn(SysExportSearch $model) => ArrayHelper::getValue(SysExport::STATUSES, $model->status),
			],
			[
				'attribute' => 'user',
				'format' => 'raw',
				'value' => static fn(SysExport $model) => BadgeWidget::widget([
					'items' => $model->relatedUser,
					'subItem' => 'username',
					'tooltip' => "id {$model->user}",
					'urlScheme' => UsersController::to('view', ['id' => $model->user])
				]),
			]
		]
	])
]) ?>
