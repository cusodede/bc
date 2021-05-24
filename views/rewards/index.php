<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var RewardsSearch $searchModel
 * @var string $modelName
 * @var ControllerTrait $controller
 * @var ActiveDataProvider $dataProvider
 */

use app\assets\ModalHelperAsset;
use app\models\reward\active_record\references\RefRewardOperations;
use app\models\reward\Rewards;
use app\models\reward\RewardsSearch;
use app\modules\status\models\StatusRulesModel;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use pozitronik\core\traits\ControllerTrait;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\ArrayHelper;
use pozitronik\helpers\Utils;
use pozitronik\widgets\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;

ModalHelperAsset::register($this);
?>
<?= GridConfig::widget([
	'id' => "{$modelName}-index-grid",
	'grid' => GridView::begin([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [
			'heading' => $this->title.(($dataProvider->totalCount > 0)
					?" (".Utils::pluralForm($dataProvider->totalCount, ['запись', 'записи', 'записей']).")"
					:" (нет записей)"),
		],
		'summary' => null !== $searchModel?Html::a('Новая запись', $controller::to('create'), [
			'class' => 'btn btn-success',
			'onclick' => new JsExpression("AjaxModal('".$controller::to('create').
				"', '{$modelName}-modal-create-new');event.preventDefault();")
		]):null,
		'showOnEmpty' => true,
		'emptyText' => Html::a('Новая запись', $controller::to('create'), [
			'class' => 'btn btn-success',
			'onclick' => new JsExpression("AjaxModal('".$controller::to('create').
				"', '{$modelName}-modal-create-new');event.preventDefault();")
		]),
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => ActionColumn::class,
				'template' => '{edit}{view}',
				'buttons' => [
					'edit' => static function(string $url, RewardsSearch $model) use ($modelName) {
						return Html::a('<i class="glyphicon glyphicon-edit"></i>', $url, [
							'onclick' => new JsExpression("AjaxModal('$url', ".
								"'{$modelName}-modal-edit-{$model->id}');event.preventDefault();")
						]);
					},
					'view' => static function(string $url, RewardsSearch $model) use ($modelName) {
						return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
							'onclick' => new JsExpression("AjaxModal('$url', ".
								"'{$modelName}-modal-view-{$model->id}');event.preventDefault();")
						]);
					},
				],
			],
			'id',
			'value',
			[
				'attribute' => 'create_date',
				'filterType' => DateTimePicker::class,
				'filterWidgetOptions' => [
					'type' => DateTimePicker::TYPE_INPUT,
					'pluginOptions' => [
						'alwaysShowCalendars' => true
					]
				]
			],
			[
				'attribute' => 'userName',
				'format' => 'raw',
				'value' => static function(RewardsSearch $model):string {
					return BadgeWidget::widget([
						'items' => $model->relatedUser,
						'subItem' => 'username'
					]);
				}
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'currentStatus',
				'value' => static function(RewardsSearch $model):string {
					return BadgeWidget::widget([
						'items' => Rewards::findOne($model->id),
						'subItem' => 'currentStatus.name'
					]);
				},
				'format' => 'raw',
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => ArrayHelper::map(StatusRulesModel::getAllStatuses(Rewards::class), 'id', 'name')
			],
			[
				'attribute' => 'operationName',
				'format' => 'raw',
				'value' => static function(RewardsSearch $model):string {
					return BadgeWidget::widget([
						'items' => $model->refRewardOperation,
						'subItem' => 'name'
					]);
				},
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'operation',
					'data' => RefRewardOperations::mapData(),
					'pluginOptions' => [
						'allowClear' => true,
						'placeholder' => ''
					]
				])
			],
			[
				'attribute' => 'ruleName',
				'format' => 'raw',
				'value' => static function(RewardsSearch $model):string {
					return BadgeWidget::widget([
						'items' => $model->refRewardRule,
						'subItem' => 'name'
					]);
				}
			],
			'comment',
			'deleted:boolean'
		]
	])
]) ?>