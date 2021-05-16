<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Stores $model
 */

use app\models\prototypes\seller\Stores;
use pozitronik\widgets\BadgeWidget;
use yii\bootstrap\Modal;
use yii\web\View;
use yii\widgets\ActiveForm;

$modelName = $model->formName();
?>
<?php Modal::begin([
	'id' => "{$modelName}-modal-create-new",
	'size' => Modal::SIZE_LARGE,
	'header' => BadgeWidget::widget([
		'items' => $model,
		'subItem' => 'id'
	]),
	'footer' => $this->render('../subviews/editPanelFooter', [
		'model' => $model,
		'form' => "{$modelName}-modal-create"
	]),//post button outside the form
	'clientOptions' => [
		'backdrop' => true
	],
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?php $form = ActiveForm::begin(['id' => "{$modelName}-modal-create"]) ?>
<?= $this->render('../subviews/editPanelBody', compact('model', 'form')) ?>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>