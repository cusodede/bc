<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use yii\base\Model;
use yii\web\View;

?>

<?= $form->field($model, 'contract_number')->textInput() ?>
<?= $form->field($model, 'contract_number_nfs')->textInput() ?>
<?= $form->field($model, 'signing_date')->widget(DatePicker::class, [
	'model' => $model,
	'options' => ['placeholder' => 'Enter event time ...'],
	'pluginOptions' => [
		'autoclose' => true,
		'format' => 'yyyy-mm-dd'
	]])
?>
