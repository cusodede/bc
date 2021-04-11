<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 */

use app\models\sys\users\Users;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(['id' => "{$model->formName()}-modal-update-password"]); ?>
	<div class="panel">
		<div class="panel-heading">
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<?= $form->field($model, 'password')->textInput(['value' => '']); ?>
				</div>
				<div class="col-md-12">
					<?= $form->field($model, 'newPassword')->textInput(); ?>
				</div>
				<div class="panel-footer">

					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>