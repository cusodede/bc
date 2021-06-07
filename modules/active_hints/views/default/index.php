<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveStorageSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\models\sys\users\Users;
use app\modules\active_hints\ActiveHintsModule;
use app\modules\active_hints\models\ActiveStorage;
use app\modules\active_hints\models\ActiveStorageSearch;
use kartik\editable\Editable;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\EditableColumn;
use kartik\grid\GridView;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\Utils;
use pozitronik\widgets\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\bootstrap4\Html;
use yii\web\View;

?>
<?= GridConfig::widget([
	'id' => 'activeHints-index-grid',
	'grid' => GridView::begin([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [
			'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['подсказка', 'подсказки', 'подсказок']).")":" (нет подсказок)"),
		],
		'summary' => null !== $searchModel?Html::a('Новая подсказка', ActiveHintsModule::to('default/create'), [
			'class' => 'btn btn-success',
		]):null,
		'showOnEmpty' => true,
		'emptyText' => Html::a('Новая подсказка', ActiveHintsModule::to('default/create'), [
			'class' => 'btn btn-success',
		]),
		'toolbar' => false,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => ActionColumn::class,
				'template' => '{edit}{view}',
				'buttons' => [
					'edit' => static function(string $url, ActiveStorage $model) {
						return Html::a('<i class="fa fa-edit"></i>', $url);
					},
				],
			],
			'id',
			'at:datetime',
			'placement',
			[
				'class' => EditableColumn::class,
				'editableOptions' => static function(ActiveStorage $model, int $key, int $index) {
					return [
						'formOptions' => [
							'action' => ActiveHintsModule::to('default/editDefault')
						],
						'inputType' => Editable::INPUT_TEXT
					];
				},
				'attribute' => 'model',
				'format' => 'text'
			],
			[
				'class' => EditableColumn::class,
				'editableOptions' => static function(ActiveStorage $model, int $key, int $index) {
					return [
						'formOptions' => [
							'action' => ActiveHintsModule::to('default/editDefault')
						],
						'inputType' => Editable::INPUT_TEXT
					];
				},
				'attribute' => 'attribute',
				'format' => 'text'
			],
			[
				'class' => EditableColumn::class,
				'editableOptions' => static function(ActiveStorage $model, int $key, int $index) {
					return [
						'formOptions' => [
							'action' => ActiveHintsModule::to('default/editDefault')
						],
						'inputType' => Editable::INPUT_TEXTAREA,
					];
				},
				'attribute' => 'content',
				'format' => 'text'
			],
			[
				'class' => EditableColumn::class,
				'editableOptions' => static function(ActiveStorage $model, int $key, int $index) {
					return [
						'formOptions' => [
							'action' => ActiveHintsModule::to('default/editDefault')
						],
						'inputType' => Editable::INPUT_TEXTAREA,
					];
				},
				'attribute' => 'header',
				'format' => 'text'
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'user',
				'value' => static function(ActiveStorage $model) {
					return BadgeWidget::widget([
						'items' => Users::findOne($model->id),
						'subItem' => 'username'
					]);
				},
				'format' => 'raw'
			]
		]
	])
]) ?>