<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var PermissionsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\controllers\PermissionsCollectionsController;
use app\controllers\PermissionsController;
use app\models\core\TemporaryHelper;
use app\models\sys\permissions\Permissions;
use app\models\sys\permissions\PermissionsSearch;
use kartik\editable\Editable;
use kartik\grid\DataColumn;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use pozitronik\core\helpers\ControllerHelper;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\ArrayHelper;
use pozitronik\helpers\Utils;
use pozitronik\widgets\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\View;

?>
<?= GridConfig::widget([
	'id' => 'permissions-index-grid',
	'grid' => GridView::begin([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [
			'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['разрешение', 'разрешения', 'разрешений']).")":" (нет разрешений)"),
		],
		'summary' => null !== $searchModel?Html::a('Новое разрешение', PermissionsController::to('create'), ['class' => 'btn btn-success summary-content']):null,
		'showOnEmpty' => true,
		'emptyText' => Html::a('Новое разрешение', PermissionsController::to('create'), ['class' => 'btn btn-success']),
		'toolbar' => [
			[
				'content' => Html::a("Редактор групп разрешений", PermissionsCollectionsController::to('index'), ['class' => 'btn pull-left btn-info'])
			]
		],
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => EditableColumn::class,
				'editableOptions' => static function(PermissionsSearch $permission, int $key, int $index) {
					return [
						'formOptions' => [
							'action' => PermissionsController::to('editDefault')
						],
						'inputType' => Editable::INPUT_TEXT
					];
				},
				'attribute' => 'name',
				'format' => 'text'
			],
			[
				'class' => EditableColumn::class,
				'editableOptions' => static function(PermissionsSearch $permission, int $key, int $index) {
					return [
						'formOptions' => [
							'action' => PermissionsController::to('editDefault')
						],
						'inputType' => Editable::INPUT_TEXTAREA,
					];
				},
				'attribute' => 'comment',
				'format' => 'text'
			],
			[
				'class' => EditableColumn::class,
				'editableOptions' => static function(PermissionsSearch $permission, int $key, int $index) {
					return [
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
					];
				},
				'attribute' => 'priority',
				'format' => 'text'
			],
			[
				'class' => EditableColumn::class,
				'editableOptions' => static function(PermissionsSearch $permission, int $key, int $index) {
					return [
						'formOptions' => [
							'action' => PermissionsController::to('editDefault')
						],
						'inputType' => Editable::INPUT_SELECT2,
						'options' => [
							'data' => ArrayHelper::map(ControllerHelper::GetControllersList('@app/controllers', null, [Controller::class]), 'id', 'id'),
							'pluginOptions' => [
								'allowClear' => true
							]
						]
					];
				},
				'attribute' => 'controller',
				'format' => 'text'
			],
			[
				'class' => EditableColumn::class,
				'editableOptions' => static function(PermissionsSearch $permission, int $key, int $index) {
					return [
						'formOptions' => [
							'action' => PermissionsController::to('editAction'),
						],
						'inputType' => Editable::INPUT_TEXT
					];
				},
				'attribute' => 'action',
				'format' => 'text'
			],
			[
				'class' => EditableColumn::class,
				'editableOptions' => static function(PermissionsSearch $permission, int $key, int $index) {
					return [
						'formOptions' => [
							'action' => PermissionsController::to('editDefault')
						],
						'inputType' => Editable::INPUT_SELECT2,
						'options' => [
							'data' => TemporaryHelper::VERBS,
							'pluginOptions' => [
								'allowClear' => true
							]
						]
					];
				},
				'attribute' => 'verb',
				'format' => 'text'
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'relatedUsersToPermissionsCollections',
				'value' => static function(PermissionsSearch $permission) {
					return BadgeWidget::widget([
						'models' => $permission->relatedPermissionsCollections,
						'attribute' => 'name'
					]);
				},
				'format' => 'raw'
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'relatedUsers',
				'value' => static function(PermissionsSearch $permission) {
					return BadgeWidget::widget([
						'models' => $permission->relatedUsers,
						'attribute' => 'username'
					]);
				},
				'format' => 'raw'
			]
		]
	])
]) ?>