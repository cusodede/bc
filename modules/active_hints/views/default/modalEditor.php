<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveStorage $storage
 * @var string $model
 * @var string $attribute
 */

use app\assets\ModalHelperAsset;
use app\modules\active_hints\models\ActiveStorage;
use yii\bootstrap4\Html;
use yii\bootstrap4\Modal;
use yii\web\JsExpression;
use yii\web\View;
use yii\bootstrap4\ActiveForm;

$id = "{$model}-{$attribute}";
ModalHelperAsset::register($this);
?>

<?php Modal::begin([
	'id' => "{$id}-modal",
	'size' => Modal::SIZE_LARGE,
	'title' => "{$model} - {$attribute}",
	'footer' => Html::submitButton('Сохранить', [
		'class' => $storage->isNewRecord?'btn btn-success float-right':'btn btn-primary float-right',
		'form' => "{$id}-form"
	]),
	'options' => [
		'class' => 'modal-dialog-large',
		'onsubmit' => new JsExpression("formSubmitAjax(event);$(this).modal('hide')")
	]
]); ?>
<?php $form = ActiveForm::begin(['id' => "{$id}-form",]) ?>
<?= $form->field($storage, 'header')->textInput() ?>
<?= $form->field($storage, 'content')->textInput() ?>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>