<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 */

use yii\bootstrap4\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

$this->title = 'Абоненты у продукта';

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
			'label' => 'ФИО',
			'format' => 'text',
			'value' => static fn($item) => $item->surname . ' ' . $item->name . ' ' . $item->patronymic,
		],
		'phone',
		[
			'attribute' => 'created_at',
			'format' => ['date', 'php:d.m.Y H:i'],
		],
	]
]) ?>
<?php Modal::end(); ?>
