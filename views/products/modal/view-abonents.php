<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 */

use app\models\abonents\Abonents;
use pozitronik\widgets\BadgeWidget;
use yii\bootstrap4\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;
use yii\widgets\Pjax;

$this->title = 'Абоненты у продукта';

?>
<?php Modal::begin([
	'size' => Modal::SIZE_LARGE,
	'title' => BadgeWidget::widget([
		'items' => 'Все пользователи продукта',
		'subItem' => 'name'
	]),
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?php Pjax::begin(); ?>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'columns' => [
		'id',
		[
			'attribute' => 'name',
			'label' => 'ФИО',
			'format' => 'text',
			'value' => static fn(Abonents $model) => $model->getFullName(),
		],
		'phone',
		[
			'attribute' => 'created_at',
			'format' => ['date', 'php:d.m.Y H:i'],
		],
	]
]) ?>
<?php Pjax::end(); ?>
<?php Modal::end(); ?>
