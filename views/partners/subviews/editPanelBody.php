<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use app\models\ref_partners_categories\active_record\RefPartnersCategories;
use kartik\form\ActiveForm;
use yii\base\Model;
use yii\web\View;
use kartik\select2\Select2;

?>

<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'name')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'inn')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'category_id')->widget(Select2::class, [
			'data' => RefPartnersCategories::mapData(),
			'pluginOptions' => [
				'multiple' => false,
				'allowClear' => true,
				'placeholder' => 'Выберите категорию партнера',
				'tags' => true
			]
		]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, 'phone')->textInput() ?>
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'email')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'comment')->textarea() ?>
	</div>
</div>
