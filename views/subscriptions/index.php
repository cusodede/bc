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
use pozitronik\core\traits\ControllerTrait;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\Utils;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use kartik\select2\Select2;
use app\models\ref_subscription_categories\active_record\RefSubscriptionCategories;

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
			'heading' => $this->title. (($dataProvider->totalCount > 0) ? ' (' . Utils::pluralForm($dataProvider->totalCount, ['подписка', 'подписки', 'подписок']). ')' : ' (нет подписок)'),
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
				'value' => 'product.partner.name',
				'label' => 'Партнер'
			],
			[
				'attribute' => 'price',
				'value' => 'product.price',
				'label' => 'Стоимость'
			],
			'trial_days_count',
			[
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'category_id',
					'data' => RefSubscriptionCategories::mapData(),
					'pluginOptions' => [
						'allowClear' => true,
						'placeholder' => ''
					]
				]),
				'attribute' => 'category_id',
				'format' => 'text',
				'value' => 'category.name',
			],
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