<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $searchModel
 * @var string $modelName
 * @var ActiveDataProvider $dataProvider
 */

use app\assets\ModalHelperAsset;
use app\models\billing_journal\BillingJournal;
use app\models\billing_journal\EnumBillingJournalStatuses;
use app\models\products\Products;
use kartik\grid\GridView;
use kartik\select2\Select2;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\View;

ModalHelperAsset::register($this);

$this->title = 'История списаний';

?>

<?= GridConfig::widget([
	'id'   => "{$modelName}-index-grid",
	'grid' => GridView::begin([
		'dataProvider'     => $dataProvider,
		'filterModel'      => $searchModel,
		'panel'            => [
			'heading' => '',
		],
		'showOnEmpty'      => true,
		'striped'          => false,
		'toolbar'          => false,
		'export'           => false,
		'resizableColumns' => true,
		'responsive'       => true,
		'columns'          => [
			'id',
			[
				'attribute' => 'searchAbonentPhone',
				'label'     => 'Телефон абонента',
				'content'   => static fn(BillingJournal $model) => $model->relatedAbonent->phone
			],
			[
				'attribute' => 'searchProductId',
				'label'     => 'Наименование продукта',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'searchProductId',
					'data'          => ArrayHelper::map(Products::find()->active()->all(), 'id', 'name'),
					'pluginOptions' => [
						'allowClear'  => true,
						'placeholder' => ''
					]
				]),
				'content'   => static fn(BillingJournal $model) => $model->relatedProduct->name
			],
			'price',
			[
				'attribute' => 'status_id',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'status_id',
					'data'          => EnumBillingJournalStatuses::mapData(),
					'pluginOptions' => [
						'allowClear'  => true,
						'placeholder' => ''
					]
				]),
				'content'   => static fn(BillingJournal $model) => $model->getStatusDesc()
			],
			[
				'attribute' => 'try_date',
				'format'    => ['date', 'php:d.m.Y H:i']
			]
		],
		'rowOptions'       => static function(BillingJournal $model) {
			return EnumBillingJournalStatuses::STATUS_FAILURE === $model->status_id ? ['class' => 'bg-warning-300'] : ['class' => 'bg-success-300'];
		}
	])
]) ?>