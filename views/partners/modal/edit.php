<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Partners $model
 */

use app\models\partners\Partners;
use pozitronik\widgets\BadgeWidget;
use yii\bootstrap4\Modal;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
<?php Modal::begin([
	'id' => "{$model->formName()}-modal-edit-{$model->id}",
	'size' => Modal::SIZE_LARGE,
	'title' => BadgeWidget::widget([
		'items' => $model,
		'subItem' => 'name'
	]),
	'footer' => $this->render('../subviews/editPanelFooter', [
		'model' => $model,
		'form' => "{$model->formName()}-modal-edit"
	]),
	'options' => [
		'class' => 'modal-dialog-large',
	]
]) ?>
<?php $form = ActiveForm::begin(['id' => "{$model->formName()}-modal-edit"]) ?>
<?= $this->render('../subviews/editPanelBody', compact('model', 'form')) ?>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>