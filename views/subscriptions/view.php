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
		'trial_days_count',
		[
			'attribute' => 'product.partner.category.name',
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
