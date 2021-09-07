<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Abonents $model
 * @var ActiveDataProvider $dataProvider
 */

use app\models\abonents\Abonents;use app\models\products\Products;
use pozitronik\widgets\BadgeWidget;
use yii\bootstrap4\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

$modelName = $model->formName();
?>
<?php Modal::begin([
	'id' => "{$modelName}-modal-view-products-{$model->id}",
	'size' => Modal::SIZE_LARGE,
	'title' => BadgeWidget::widget([
		'items' => 'Все активные продукты абонента',
		'subItem' => 'name'
	]),
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'columns' => [
		'id',
		[
			'attribute' => 'name',
			'format' => 'raw',
			'value' => static function(Products $item) use ($model) {
				return Html::a($item->name, ['products/journal', 'ProductsJournalSearch' => ['searchAbonentPhone' => $model->phone]]);
			},
		],
		'price',
		'description',
		[
			'attribute' => 'created_at',
			'format' => ['date', 'php:d.m.Y H:i'],
		],
	]
]) ?>
<?php Modal::end(); ?>
