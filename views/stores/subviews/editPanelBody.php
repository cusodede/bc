<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Stores $model
 * @var ActiveForm $form
 */

use app\controllers\SellersController;
use app\models\seller\seller\Sellers;
use app\models\store\active_record\references\RefStoreTypes;
use app\models\store\Stores;
use app\widgets\selectmodelwidget\SelectModelWidget;
use kartik\form\ActiveForm;
use pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use yii\web\View;

?>

<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'name')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'type')->widget(ReferenceSelectWidget::class, [
			'referenceClass' => RefStoreTypes::class
		]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'sellers')->widget(SelectModelWidget::class, [
			'loadingMode' => SelectModelWidget::DATA_MODE_AJAX,
			'selectModelClass' => Sellers::class,
			'options' => ['placeholder' => ''],
			'ajaxSearchUrl' => SellersController::to('ajax-search')
		]) ?>
	</div>
</div>
