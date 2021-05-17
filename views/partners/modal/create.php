<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Partners $model
 * @var ActiveForm|string $form
 */

use app\models\partners\Partners;
use yii\bootstrap4\Modal;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<?php Modal::begin([
	'id' => "{$model->formName()}-modal-create-new",
	'size' => Modal::SIZE_LARGE,
	'title' => 'Новый партнер',
	'footer' => Html::submitButton('Сохранить', [
			'class' => $model->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right',
			'form' => "{$model->formName()}-modal-create",
		]
	),
	'options' => [
		'class' => 'modal-dialog-large',
	]
]) ?>
<?php $form = ActiveForm::begin(['id' => "{$model->formName()}-modal-create"]) ?>
<?= $this->render('../subviews/editPanelBody', compact('model', 'form')) ?>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>