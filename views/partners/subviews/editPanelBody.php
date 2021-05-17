<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Partners $model
 * @var ActiveForm $form
 */

use app\models\partners\Partners;
use kartik\form\ActiveForm;
use yii\web\View;

?>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'name')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'inn', ['enableAjaxValidation' => true])->textInput() ?>
	</div>
</div>
