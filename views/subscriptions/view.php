<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 */

use yii\base\Model;
use yii\web\View;
use yii\widgets\DetailView;

?>

<?= DetailView::widget([
	'model' => $model,
	'attributes' => [
		[
			'attribute' => 'product.name',
			'label' => 'Наименование',
		],
		[
			'attribute' => 'product.price',
			'label' => 'Стоимость',
		],
		[
			'attribute' => 'category.name',
			'label' => 'Категория',
		],
		[
			'attribute' => 'product.partner.name',
			'label' => 'Партнер',
		],
		[
			'attribute' => 'created_at',
			'format' => ['date', 'php:d.m.Y H:i'],
		],
		[
			'attribute' => 'updated_at',
			'format' => ['date', 'php:d.m.Y H:i'],
		],
	],
]) ?>
