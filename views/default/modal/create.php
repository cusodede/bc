<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var string|null $title
 */

use yii\base\Model;
use yii\bootstrap4\Modal;
use yii\web\JsExpression;
use yii\web\View;
use yii\bootstrap4\ActiveForm;

$modelName = $model->formName();
?>
<?php Modal::begin([
	'id' => "{$modelName}-modal-create-new",
	'size' => Modal::SIZE_LARGE,
	'title' => $title ?? '',
	'footer' => $this->render(Yii::$app->controller->viewPath . '/subviews/editPanelFooter', [
		'model' => $model,
		'form' => "{$modelName}-modal-create"
	]),
	'options' => [
		'class' => 'modal-dialog-large',
		'tabindex' => false
	]
]); ?>
<?php $form = ActiveForm::begin(
	[
		'id' => "{$modelName}-modal-create",
		'enableAjaxValidation' => true,
		'options' => [
			'class' => 'form-ajax-submit'
		]
	])
?>
<?= $this->render(Yii::$app->controller->viewPath . '/subviews/editPanelBody', compact('model', 'form')) ?>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>