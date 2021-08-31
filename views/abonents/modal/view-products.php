<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $phone
 * @var ActiveDataProvider $dataProvider
 */

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
			'value' => static function($item) use ($phone) {
				return Html::a($item->name, ["products/journal?ProductsJournalSearch%5Bid%5D=&ProductsJournalSearch%5BsearchAbonentPhone%5D=%2B{$phone}"]);
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
