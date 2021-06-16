<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DealersSearch $searchModel
 * @var string $modelName
 * @var ControllerTrait $controller
 * @var ActiveDataProvider $dataProvider
 */

use app\assets\ModalHelperAsset;
use app\controllers\ManagersController;
use app\controllers\SellersController;
use app\controllers\StoresController;
use app\models\dealers\DealersSearch;
use kartik\grid\GridView;
use pozitronik\core\traits\ControllerTrait;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\Utils;
use pozitronik\widgets\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\bootstrap4\Html;
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
					'edit' => static function(string $url, DealersSearch $model) use ($modelName) {
						return Html::a('<i class="fa fa-edit"></i>', $url, [
							'onclick' => new JsExpression("AjaxModal('$url', '{$modelName}-modal-edit-{$model->id}');event.preventDefault();")
						]);
					},
					'view' => static function(string $url, DealersSearch $model) use ($modelName) {
						return Html::a('<i class="fa fa-eye"></i>', $url, [
							'onclick' => new JsExpression("AjaxModal('$url', '{$modelName}-modal-view-{$model->id}');event.preventDefault();")
						]);
					},
				],
			],
			'id',
			'name',
			'code',
			'client_code',
			[
				'attribute' => 'group',
				'format' => 'raw',
				'value' => static function(DealersSearch $model, int $key, int $index) {
					return BadgeWidget::widget([
						'items' => $model->refDealersGroups,
						'subItem' => 'name'
					]);
				}
			],
			[
				'attribute' => 'type',
				'format' => 'raw',
				'value' => static function(DealersSearch $model, int $key, int $index) {
					return BadgeWidget::widget([
						'items' => $model->refDealersTypes,
						'subItem' => 'name'
					]);
				}
			],
			[
				'attribute' => 'branch',
				'format' => 'raw',
				'value' => static function(DealersSearch $model, int $key, int $index) {
					return BadgeWidget::widget([
						'items' => $model->refBranches,
						'subItem' => 'name'
					]);
				}
			],
			[
				'attribute' => 'stores',
				'format' => 'raw',
				'value' => static function(DealersSearch $model):string {
					return BadgeWidget::widget([
						'items' => $model->stores,
						'subItem' => 'name',
						'urlScheme' => [StoresController::to('index'), 'StoresSearch[id]' => 'id']
					]);
				}
			],
			[
				'attribute' => 'managers',
				'format' => 'raw',
				'value' => static function(DealersSearch $model):string {
					return BadgeWidget::widget([
						'items' => $model->managers,
						'subItem' => 'fio',
						'urlScheme' => [ManagersController::to('index'), 'ManagersSearch[id]' => 'id']
					]);
				}
			],
			[
				'attribute' => 'seller',
				'format' => 'raw',
				'value' => static function(DealersSearch $model):string {
					return BadgeWidget::widget([
						'items' => $model->sellers,
						'subItem' => 'fio',
						'urlScheme' => [SellersController::to('index'), 'SellersSearch[id]' => 'id']
					]);
				}
			],
		]
	]),
]) ?>