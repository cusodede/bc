<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Subscriptions $model
 */

use yii\bootstrap4\Modal;
use yii\web\View;
use kartik\form\ActiveForm;
use app\models\subscriptions\Subscriptions;

$modelName = $model->formName();
?>
<?php Modal::begin([
	'id' => "{$modelName}-modal-create-new",
	'size' => Modal::SIZE_LARGE,
	'title' => 'Новая подписка',
	'footer' => $this->render('../subviews/editPanelFooter', [
		'model' => $model,
		'form' => "{$modelName}-modal-create"
	]),
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?php $form = ActiveForm::begin([
	'id' => "{$modelName}-modal-create",
	'enableAjaxValidation' => true,
	'validateOnChange' => false,
	'validateOnBlur' => false,
]); ?>
<?= $this->render('../subviews/editPanelBody', compact('model', 'form')) ?>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>