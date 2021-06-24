<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use kartik\form\ActiveForm;
use pozitronik\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\View;
use kartik\select2\Select2;
use app\models\partners\Partners;

?>

<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'name')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'price')->textInput(['type' => 'number']) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'description')->textarea() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'partner_id')->widget(Select2::class, [
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
