<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Subscriptions $model
 * @var ActiveForm $form
 */

use kartik\form\ActiveForm;
use pozitronik\helpers\ArrayHelper;
use app\models\subscriptions\Subscriptions;
use yii\web\View;
use kartik\select2\Select2;
use app\models\partners\Partners;
use kartik\touchspin\TouchSpin;

?>

<div class="row">
	<div class="col-md-12">
		<?= $form->field($model->product, 'name')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model->product, 'description')->textarea() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<?= $form->field($model->product, 'price')->textInput(['type' => 'number']) ?>
	</div>
	<div class="col-md-8">
		<?= $form->field($model->product, 'partner_id')->widget(Select2::class, [
			'data' => ArrayHelper::map(Partners::find()->active()->all(), 'id', 'name'),
			'pluginOptions' => [
				'multiple' => false,
				'allowClear' => true,
				'placeholder' => 'Выберите партнера',
				'tags' => true
			]
		]) ?>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<?= $form->field($model, 'trial_days_count')->widget(TouchSpin::class) ?>
	</div>
</div>

