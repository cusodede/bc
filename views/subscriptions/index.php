<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $searchModel
 * @var string $modelName
 * @var ControllerTrait $controller
 * @var ActiveDataProvider $dataProvider
 */

use app\assets\ModalHelperAsset;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use pozitronik\grid_config\GridConfig;
use pozitronik\traits\traits\ControllerTrait;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;

ModalHelperAsset::register($this);
$this->title = 'Подписки';
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
		'summary' => null !== $searchModel ? Html::a('Создать подписку', $controller::to('create'), [
			'class' => 'btn btn-success',
			'onclick' => new JsExpression("AjaxModal('".$controller::to('create')."', '{$modelName}-modal-create-new');event.preventDefault();")
		]) : null,
		'showOnEmpty' => true,
		'emptyText' => Html::a('Создать подписку', $controller::to('create'), [
			'class' => 'btn btn-success',
			'onclick' => new JsExpression("AjaxModal('".$controller::to('create')."', '{$modelName}-modal-create-new');event.preventDefault();")
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
					'edit' => static function(string $url, Model $model): string
					{
						return Html::a('<i class="fas fa-edit"></i>', $url, [
							'onclick' => new JsExpression("AjaxModal('$url', '{$model->formName()}-modal-edit-{$model->id}');event.preventDefault();")
						]);
					},
					'view' => static function(string $url, Model $model): string
					{
						return Html::a('<i class="fas fa-eye"></i>', $url, [
							'onclick' => new JsExpression("AjaxModal('$url', '{$model->formName()}-modal-view-{$model->id}');event.preventDefault();")
						]);
					},
				],
			],
			'id',
			[
				'attribute' => 'partner_id',
				'value' => 'product.name',
				'label' => 'Наименование продукта'
			],
			[
				'attribute' => 'partner_id',
				'value' => 'product.relatedPartner.name',
				'label' => 'Партнер'
			],
			[
				'attribute' => 'price',
				'value' => 'product.price',
				'label' => 'Стоимость'
			],
			'trial_count',
			[
				'class' => DataColumn::class,
				'attribute' => 'product.created_at',
				'format' => ['date', 'php:d.m.Y H:i'],
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'product.updated_at',
				'format' => ['date', 'php:d.m.Y H:i'],
			],
		],
	])
]) ?>