<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var SysExportAR $model
 */

use app\modules\export\models\active_record\SysExportAR;
use app\widgets\badgewidget\BadgeWidget;
use yii\bootstrap4\Modal;
use yii\web\View;

$modelName = $model->formName();
?>
<?php Modal::begin([
	'id' => "{$modelName}-modal-view-{$model->id}",
	'size' => Modal::SIZE_LARGE,
	'title' => BadgeWidget::widget([
		'items' => $model,
		'subItem' => 'id'
	]),
	'clientOptions' => [
		'backdrop' => true
	],
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?= $this->render('../view', compact('model')) ?>
<?php Modal::end(); ?>