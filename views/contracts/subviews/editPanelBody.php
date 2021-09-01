<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use app\models\products\Products;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use pozitronik\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\View;

?>

<?= $form->field($model, 'contract_number')->textInput() ?>
<?= $form->field($model, 'contract_number_nfs')->textInput() ?>
<?= $form->field($model, 'relatedProducts')->widget(Select2::class, [
	'data' => ArrayHelper::map(Products::find()->active()->all(), 'id', 'name'),
	'pluginOptions' => [
		'placeholder' => 'Выберите продукты',
		'multiple' => true,
		'allowClear' => true,
		'tags' => true
	]
]) ?>
<?= $form->field($model, 'signing_date')->widget(DatePicker::class, [
	'model' => $model,
	'options' => ['placeholder' => 'Enter event time ...'],
	'pluginOptions' => [
		'autoclose' => true,
		'format' => 'yyyy-mm-dd'
	]])
?>
