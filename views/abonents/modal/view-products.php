<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 */

use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Продукты абонента';

?>
<?php Modal::begin([
	'size' => Modal::SIZE_LARGE,
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
			'value' => function($model) {
				return Html::a($model->name, ['products/journal']);
			},
		],
		'price',
		'description',
		[
			'attribute' => 'created_at',
			'format' => ['date', 'php:d.m.Y H:i'],
		],
	]
]); ?>
<?php Modal::end(); ?>
