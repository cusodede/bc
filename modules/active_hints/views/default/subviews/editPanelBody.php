<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveStorage $model
 * @var ActiveForm $form
 */

use app\modules\active_hints\models\ActiveStorage;
use kartik\form\ActiveForm;
use yii\web\View;

?>

<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'model')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'attribute')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'content')->textarea() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'header')->textInput() ?>
	</div>
</div>

