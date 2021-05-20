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
use app\models\ref_products_types\active_record\RefProductsTypes;
use app\models\partners\Partners;
use pozitronik\helpers\ArrayHelper;

ModalHelperAsset::register($this);
?>
<?= GridConfig::widget([
	'id' => "{$modelName}-index-grid",
	'grid' => GridView::begin([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [
			'heading' => $this->title. (($dataProvider->totalCount > 0) ? ' (' . Utils::pluralForm($dataProvider->totalCount, ['продукт', 'продукты', 'продуктов']). ')' : ' (нет продуктов)'),
		],
		'summary' => $searchModel !== null ? Html::a('Добавить продукт', $controller::to('create'), [
			'class' => 'btn btn-success',
			'onclick' => new JsExpression("AjaxModal('".$controller::to('create')."', '{$modelName}-modal-create-new');event.preventDefault();")
		]):null,
		'showOnEmpty' => true,
		'emptyText' => Html::a('Добавить продукт', $controller::to('create'), [
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
					'edit' => static function(string $url, Model $model)
					{
						return Html::a('<i class="fas fa-edit"></i>', $url, [
							'onclick' => new JsExpression("AjaxModal('$url', '{$model->formName()}-modal-edit-{$model->id}');event.preventDefault();")
						]);
					},
					'view' => static function(string $url, Model $model)
					{
						return Html::a('<i class="fas fa-eye"></i>', $url, [
							'onclick' => new JsExpression("AjaxModal('$url', '{$model->formName()}-modal-view-{$model->id}');event.preventDefault();")
						]);
					},
				],
			],
			'id',
			'name',
			[
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'type_id',
					'data' => RefProductsTypes::mapData(),
					'pluginOptions' => [
						'allowClear' => true,
						'placeholder' => ''
					]
				]),
				'attribute' => 'type_id',
				'format' => 'text',
				'value' => 'type.name',
			],
			[
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'partner_id',
					'data' => ArrayHelper::map(Partners::find()->active()->all(), 'id', 'name'),
					'pluginOptions' => [
						'allowClear' => true,
						'placeholder' => ''
					]
				]),
				'attribute' => 'partner_id',
				'format' => 'text',
				'value' => 'partner.name',
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'created_at',
				'format' => ['date', 'php:d.m.Y H:i'],
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'updated_at',
				'format' => ['date', 'php:d.m.Y H:i'],
			],
		],
	])
]) ?>