<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Abonents $model
 */

use app\models\abonents\Abonents;
use pozitronik\widgets\BadgeWidget;
use yii\bootstrap4\Modal;
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
<?= $this->render('../view', compact('model')) ?>
<?php Modal::end(); ?>