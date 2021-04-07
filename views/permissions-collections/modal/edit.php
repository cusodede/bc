<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var PermissionsCollections $model
 */

use app\controllers\PermissionsCollectionsController;
use app\models\sys\permissions\active_record\PermissionsCollections;
use pozitronik\widgets\BadgeWidget;
use yii\bootstrap\Modal;
use yii\web\View;
use yii\widgets\ActiveForm;

?>
<?php Modal::begin([
	'id' => "permissions-collections-modal-edit-{$model->id}",
	'size' => Modal::SIZE_LARGE,
	'header' => BadgeWidget::widget([
		'models' => $model,
		'attribute' => 'name',
		'itemsSeparator' => '',
	]),
	'footer' => $this->render('../subviews/editPanelFooter', [
		'model' => $model,
		'form' => 'permissions-collections-modal-edit'
	]),//post button outside the form
	'clientOptions' => [
		'backdrop' => true
	],
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?php $form = ActiveForm::begin(['id' => 'permissions-collections-modal-edit', 'action' => PermissionsCollectionsController::to('edit', ['id' => $model->id])]) ?>
<?= $this->render('../subviews/editPanelBody', compact('model', 'form')) ?>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>