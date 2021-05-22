<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Rewards $model
 * @var ActiveForm $form
 */

use app\controllers\UsersController;
use app\models\reward\active_record\references\RefRewardStatuses;
use app\models\reward\active_record\references\RefRewardOperations;
use app\models\reward\active_record\references\RefRewardRules;
use app\models\reward\Rewards;
use app\widgets\selectmodelwidget\SelectModelWidget;
use kartik\form\ActiveForm;
use  app\models\sys\users\Users;
use pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use yii\web\View;

?>

<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'value')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'user')->widget(SelectModelWidget::class, [
			'loadingMode' => SelectModelWidget::DATA_MODE_AJAX,
			'selectModelClass' => Users::class,
			'mapAttribute' => 'username',
			'options' => ['placeholder' => '', 'multiple' => false],
			'ajaxSearchUrl' => UsersController::to('ajax-search')
		]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'status')->widget(ReferenceSelectWidget::class, [
			'referenceClass' => RefRewardStatuses::class
		]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'operation')->widget(ReferenceSelectWidget::class, [
			'referenceClass' => RefRewardOperations::class
		]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'rule')->widget(ReferenceSelectWidget::class, [
			'referenceClass' => RefRewardRules::class
		]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
	</div>
</div>
