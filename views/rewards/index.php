<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Rewards $searchModel
 * @var string $modelName
 * @var ControllerTrait $controller
 * @var ActiveDataProvider $dataProvider
 */

use app\assets\ModalHelperAsset;
use app\models\reward\Rewards;
use app\models\reward\RewardsSearch;
use kartik\grid\GridView;
use pozitronik\core\traits\ControllerTrait;
use pozitronik\grid_config\GridConfig;
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
			'create_date',
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
				'attribute' => 'statusName',
				'format' => 'raw',
				'value' => static function(RewardsSearch $model):string {
					return BadgeWidget::widget([
						'items' => $model->refRewardStatus,
						'subItem' => 'name'
					]);
				}
			],
			[
				'attribute' => 'operationName',
				'format' => 'raw',
				'value' => static function(RewardsSearch $model):string {
					return BadgeWidget::widget([
						'items' => $model->refRewardOperation,
						'subItem' => 'name'
					]);
				}
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