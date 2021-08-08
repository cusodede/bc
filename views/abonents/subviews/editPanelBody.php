<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use kartik\form\ActiveForm;
use yii\base\Model;
use yii\web\View;
use yii\widgets\MaskedInput;

?>

<?= $form->field($model, 'surname')->textInput() ?>
<?= $form->field($model, 'name')->textInput() ?>
<?= $form->field($model, 'patronymic')->textInput() ?>
<?= $form->field($model, 'phone')->widget(MaskedInput::class, [
	'mask' => '+7 (999) 999-99-99'
]) ?>
