<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Sellers $searchModel
 * @var string $modelName
 * @var ControllerTrait $controller
 * @var ActiveDataProvider $dataProvider
 */

use app\assets\ModalHelperAsset;
use app\controllers\StoresController;
use app\models\seller\seller\active_record\Sellers;
use app\models\seller\seller\SellersSearch;
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
			'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['запись', 'записи', 'записей']).")":" (нет записей)"),
		],
		'summary' => null !== $searchModel?Html::a('Новая запись', $controller::to('create'), [
			'class' => 'btn btn-success',
			'onclick' => new JsExpression("AjaxModal('".$controller::to('create')."', '{$modelName}-modal-create-new');event.preventDefault();")
		]):null,
		'showOnEmpty' => true,
		'emptyText' => Html::a('Новая запись', $controller::to('create'), [
			'class' => 'btn btn-success',
			'onclick' => new JsExpression("AjaxModal('".$controller::to('create')."', '{$modelName}-modal-create-new');event.preventDefault();")
		]),
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => ActionColumn::class,
				'template' => '{edit}{view}',
				'buttons' => [
					'edit' => static function(string $url, SellersSearch $model) use ($modelName):string {
						return Html::a('<i class="glyphicon glyphicon-edit"></i>', $url, [
							'onclick' => new JsExpression("AjaxModal('$url', '{$modelName}-modal-edit-{$model->id}');event.preventDefault();")
						]);
					},
					'view' => static function(string $url, SellersSearch $model) use ($modelName):string {
						return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
							'onclick' => new JsExpression("AjaxModal('$url', '{$modelName}-modal-view-{$model->id}');event.preventDefault();")
						]);
					},
				],
			],
			'id',
			'name',
			[
				'attribute' => 'stores',
				'format' => 'raw',
				'value' => static function(SellersSearch $model):string {
					return BadgeWidget::widget([
						'items' => $model->stores,
						'subItem' => 'name',
						'urlScheme' => [StoresController::to('view'), 'id' => 'id'],
						'options' => static function($mapAttributeValue, $model):array {
							$url = StoresController::to('view', ['id' => $model->id]);
							return [
								'onclick' => new JsExpression("AjaxModal('$url', '{$model->formName()}-modal-view-{$model->id}');event.preventDefault();")
							];
						}
					]);
				}
			]
		],
	])
]) ?>