<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Stores $model
 * @var ActiveForm $form
 */

use app\models\prototypes\seller\active_record\references\RefStoreTypes;
use app\models\prototypes\seller\Stores;
use kartik\form\ActiveForm;
use pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use yii\web\View;

?>

<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'name')->textInput() ?>
	</div>
	<div class="col-md-12">
		<?= $form->field($model, 'type')->widget(ReferenceSelectWidget::class, [
			'referenceClass' => RefStoreTypes::class
		]) ?>
	</div>
</div>
