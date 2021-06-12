<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Rewards $model
 * @var ActiveForm $form
 */

use app\controllers\UsersController;
use app\models\reward\active_record\references\RefRewardsOperations;
use app\models\reward\active_record\references\RefRewardsRules;
use app\models\reward\Rewards;
use app\widgets\selectmodelwidget\SelectModelWidget;
use kartik\form\ActiveForm;
use  app\models\sys\users\Users;
use kartik\select2\Select2;
use pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use yii\helpers\ArrayHelper;
use yii\web\View;

?>

<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'quantity')->textInput() ?>
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
		<?= $form->field($model, 'currentStatusId')->widget(Select2::class, [
			'data' => ArrayHelper::map(
				$model->getAvailableStatuses(),
				'id',
				'name'
			)
		]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'operation')->widget(ReferenceSelectWidget::class, [
			'referenceClass' => RefRewardsOperations::class
		]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'rule')->widget(ReferenceSelectWidget::class, [
			'referenceClass' => RefRewardsRules::class
		]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
	</div>
</div>
