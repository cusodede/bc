<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Abonents $model
 * @var ActiveDataProvider $dataProvider
 */

use app\models\abonents\Abonents;use app\models\products\Products;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Продукты абонента';
$modelName = $model->formName();
?>

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
