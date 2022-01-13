<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var PermissionsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\assets\GridHelperAsset;
use app\assets\ModalHelperAsset;
use app\components\grid\ActionColumn;
use app\components\grid\widgets\toolbar_filter_widget\ToolbarFilterWidget;
use app\components\helpers\Html;
use app\components\helpers\TemporaryHelper;
use app\controllers\PermissionsCollectionsController;
use app\controllers\PermissionsController;
use app\controllers\UsersController;
use app\models\sys\permissions\Permissions;
use app\models\sys\permissions\PermissionsSearch;
use app\widgets\badgewidget\BadgeWidget;
use kartik\editable\Editable;
use kartik\grid\DataColumn;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use kartik\select2\Select2;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\Utils;
use yii\data\ActiveDataProvider;
use yii\web\JsExpression;
use yii\web\View;

ModalHelperAsset::register($this);
GridHelperAsset::register($this);

$id = 'permissions-index-grid';
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
			'{totalCount}' => ($dataProvider->totalCount > 0)?Utils::pluralForm($dataProvider->totalCount, ['разрешение', 'разрешения', 'разрешений']):"Нет разрешений",
			'{newRecord}' => ToolbarFilterWidget::widget([
				'label' => ($dataProvider->totalCount > 0)?Utils::pluralForm($dataProvider->totalCount, ['разрешение', 'разрешения', 'разрешений']):"Нет разрешений",
				'content' => Html::link('Новая запись', PermissionsController::to('create'), ['class' => 'btn btn-success'])
			]),
			'{filterBtn}' => ToolbarFilterWidget::widget(['content' => Html::button("<i class='fa fa-filter'></i>", ['onclick' => new JsExpression('setFakeGridFilter("#'.$id.'")'), 'class' => 'btn btn-info'])]),
			'{collectionsLink}' => ToolbarFilterWidget::widget(['content' => Html::link('Редактор групп', PermissionsCollectionsController::to('index'), ['class' => 'btn btn-info'])])
		],
		'toolbar' => [
			'{filterBtn}'
		],
		'panelBeforeTemplate' => '{optionsBtn}{newRecord}{collectionsLink}{toolbarContainer}{before}<div class="clearfix"></div>',
		'summary' => null,
		'showOnEmpty' => true,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => ActionColumn::class,
				'template' => '<div class="btn-group">{edit}{delete}</div>',
			],
			'id',
			[
				'class' => EditableColumn::class,
				'editableOptions' => static fn(Permissions $permission, int $key, int $index) => [
					'formOptions' => [
						'action' => PermissionsController::to('editDefault')
					],
					'inputType' => Editable::INPUT_TEXT
				],
				'attribute' => 'name',
				'format' => 'text'
			],
			[
				'class' => EditableColumn::class,
				'editableOptions' => static fn(Permissions $permission, int $key, int $index) => [
					'formOptions' => [
						'action' => PermissionsController::to('editDefault')
					],
					'inputType' => Editable::INPUT_SPIN,
					'options' => [
						'pluginOptions' => [
							'min' => Permissions::PRIORITY_MIN,
							'max' => Permissions::PRIORITY_MAX
						]
					]
				],
				'attribute' => 'priority',
				'format' => 'text'
			],
			[
				'class' => EditableColumn::class,
				'editableOptions' => static fn(Permissions $permission, int $key, int $index) => [
					'formOptions' => [
						'action' => PermissionsController::to('editDefault')
					],
					'inputType' => Editable::INPUT_SELECT2,
					'options' => [
						'data' => TemporaryHelper::GetControllersList(Permissions::ConfigurationParameter(Permissions::CONTROLLER_DIRS)),
						'pluginOptions' => [
							'multiple' => false,
							'allowClear' => true,
							'placeholder' => '',
							'tags' => true
						]
					]
				],
				'attribute' => 'controller',
				'format' => 'text'
			],
			[
				'class' => EditableColumn::class,
				'editableOptions' => static fn(Permissions $permission, int $key, int $index) => [
					'formOptions' => [
						'action' => PermissionsController::to('editAction'),
					],
					'inputType' => Editable::INPUT_TEXT
				],
				'attribute' => 'action',
				'format' => 'text'
			],
			[
				'class' => EditableColumn::class,
				'editableOptions' => static fn(Permissions $permission, int $key, int $index) => [
					'formOptions' => [
						'action' => PermissionsController::to('editDefault')
					],
					'inputType' => Editable::INPUT_SELECT2,
					'options' => [
						'data' => TemporaryHelper::VERBS,
						'pluginOptions' => [
							'multiple' => false,
							'allowClear' => true,
							'placeholder' => '',
							'tags' => true
						]
					]
				],
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'verb',
					'data' => TemporaryHelper::VERBS,
					'pluginOptions' => [
						'allowClear' => true,
						'placeholder' => ''
					]
				]),
				'attribute' => 'verb',
				'format' => 'text'
			],
			[
				'attribute' => 'module'
			],
			['class' => EditableColumn::class,
				'editableOptions' => static fn(Permissions $permission, int $key, int $index) => [
					'formOptions' => [
						'action' => PermissionsController::to('editDefault')
					],
					'inputType' => Editable::INPUT_TEXTAREA,
				],
				'attribute' => 'comment',
				'format' => 'text'
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'collection',
				'label' => 'Входит в группы',
				'value' => static fn(Permissions $permission) => BadgeWidget::widget([
					'items' => $permission->relatedPermissionsCollections,
					'subItem' => 'name',
					'urlScheme' => [PermissionsCollectionsController::to('edit'), 'id' => 'id']
				]),
				'format' => 'raw'
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'user',
				'label' => 'Назначено пользователям',
				'value' => static fn(Permissions $permission) => BadgeWidget::widget([
					'items' => $permission->relatedUsers,
					'subItem' => 'username',
					'urlScheme' => [UsersController::to('view'), 'id' => 'id']
				]),
				'format' => 'raw'
			]
		]
	])
]) ?>
