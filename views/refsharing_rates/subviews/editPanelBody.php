<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use app\models\products\Products;
use app\models\refsharing_rates\EnumRevShareType;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use pozitronik\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\View;

?>

<div class="row mb-4">
	<div class="col-sm-6">
		<?= $form->field($model, 'type')->dropDownList(EnumRevShareType::mapData()) ?>
	</div>
	<div class="col-sm-6">
		<?= $form->field($model, 'ref_share')->textInput(['type' => 'number', 'min' => 0, 'max' => 1, 'step' => 0.1]) ?>
	</div>
</div>
<?= $form->field($model, 'value')->textInput() ?>
<?= $form->field($model, 'product_id')->widget(Select2::class, [
	'data' => ArrayHelper::map(Products::find()->active()->all(), 'id', 'name'),
	'pluginOptions' => [
		'placeholder' => 'Выберите продукт',
		'allowClear' => true,
		'tags' => true
	]
]) ?>
