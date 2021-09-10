<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Partners $model
 */

use app\models\partners\Partners;
use pozitronik\widgets\BadgeWidget;
use yii\bootstrap4\Modal;
use yii\bootstrap4\Tabs;
use yii\web\View;

$modelName = $model->formName();
?>
<?php Modal::begin([
	'id' => "{$modelName}-modal-view-{$model->id}",
	'size' => Modal::SIZE_LARGE,
	'title' => BadgeWidget::widget([
		'items' => $model,
		'subItem' => 'name'
	]),
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?= Tabs::widget([
	'items' => [
		[
			'label' => 'Инфо',
			'content' => $this->render('../view', compact('model')),
			'active' => true
		],
		[
			'label' => 'Договор',
			'content' => $this->render('../view-contract', compact('model'))
		]
	]
]) ?>
<?php Modal::end(); ?>