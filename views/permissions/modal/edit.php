<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Permissions $model
 */

use app\controllers\PermissionsController;
use app\models\sys\permissions\Permissions;
use pozitronik\widgets\BadgeWidget;
use yii\bootstrap\Modal;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
<?php Modal::begin([
	'id' => "permissions-modal-edit-{$model->id}",
	'size' => Modal::SIZE_LARGE,
	'header' => BadgeWidget::widget([
		'models' => $model,
		'attribute' => 'name',
		'itemsSeparator' => '',
	]),
	'footer' => $this->render('../subviews/editPanelFooter', [
		'model' => $model,
		'form' => 'permissions-modal-edit'
	]),//post button outside the form
	'clientOptions' => [
		'backdrop' => true
	],
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?php $form = ActiveForm::begin(['id' => 'permissions-modal-edit', 'action' => PermissionsController::to('edit', ['id' => $model->id])]) ?>
<?= $this->render('../subviews/editPanelBody', [
	'model' => $model,
	'form' => $form
]) ?>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>