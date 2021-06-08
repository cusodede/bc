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
		'product.description',
		[
			'attribute' => 'product.price',
			'label' => 'Стоимость',
		],
		[
			'attribute' => 'trial',
			'label' => 'Триальный период',
			'format' => 'boolean',
		],
		'trial_days_count',
		[
			'attribute' => 'category.name',
			'label' => 'Категория',
		],
		[
			'attribute' => 'product.partner.name',
			'label' => 'Партнер',
		],
		[
			'attribute' => 'product.created_at',
			'format' => ['date', 'php:d.m.Y H:i'],
		],
		[
			'attribute' => 'product.updated_at',
			'format' => ['date', 'php:d.m.Y H:i'],
		],
	],
]) ?>
