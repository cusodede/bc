<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $phone
 * @var ActiveDataProvider $dataProvider
 */

use app\models\products\Products;
use pozitronik\widgets\BadgeWidget;
use yii\bootstrap4\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Продукты абонента';

?>
<?php Modal::begin([
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
			'value' => static function(Products $item) use ($phone) {
				return Html::a($item->name, ['products/journal', 'ProductsJournalSearch' => ['searchAbonentPhone' => $phone]]);
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
