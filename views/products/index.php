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
use yii\web\View;
use kartik\select2\Select2;
use app\models\partners\Partners;
use pozitronik\helpers\ArrayHelper;
use app\models\products\EnumProductsTypes;
use app\models\products\Products;

ModalHelperAsset::register($this);
$this->title = 'Продукты';
$this->params['breadcrumbs'][] = $this->title;

?>
<?= GridConfig::widget([
	'id' => "{$modelName}-index-grid",
	'grid' => GridView::begin([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [
			'heading' => $this->title. (($dataProvider->totalCount > 0) ? ' (' . Utils::pluralForm($dataProvider->totalCount, ['продукт', 'продукты', 'продуктов']). ')' : ' (нет продуктов)'),
		],
		'showOnEmpty' => true,
		'toolbar' => false,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			'id',
			'name',
			'price',
			[
				'filter' => Select2::widget([
					'model' => $searchModel,
					'attribute' => 'type_id',
					'data' => EnumProductsTypes::PRODUCTS_TYPES,
					'pluginOptions' => [
						'allowClear' => true,
						'placeholder' => ''
					]
				]),
				'attribute' => 'type_id',
				'format' => 'text',
				'value' => static fn(Products $product) => EnumProductsTypes::getType($product->type_id),
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