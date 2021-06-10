<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Managers $model
 * @var ActiveForm $form
 */

use app\controllers\StoresController;
use app\models\managers\Managers;
use app\models\store\Stores;
use app\widgets\selectmodelwidget\SelectModelWidget;
use kartik\form\ActiveForm;
use yii\web\View;

?>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'name')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'surname')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'patronymic')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model->relatedUser??$model, 'login')
			->textInput(['readonly' => !$model->isNewRecord, 'placeholder' => '9123456789']) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model->relatedUser??$model, 'email')->textInput(['readonly' => !$model->isNewRecord]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'stores')->widget(SelectModelWidget::class, [
			'loadingMode' => SelectModelWidget::DATA_MODE_AJAX,
			'selectModelClass' => Stores::class,
			'options' => ['placeholder' => ''],
			'ajaxSearchUrl' => StoresController::to('ajax-search')
		]) ?>
	</div>
</div>