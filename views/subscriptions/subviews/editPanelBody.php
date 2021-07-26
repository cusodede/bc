<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Subscriptions $model
 * @var ActiveForm $form
 */

use kartik\form\ActiveForm;
use kartik\touchspin\TouchSpin;
use app\models\subscriptions\Subscriptions;
use app\models\subscriptions\EnumSubscriptionTrialUnits;
use yii\web\View;

?>

<?= $this->render('../../products/subviews/editPanelBody', ['model' => $model->product, 'form' => $form]) ?>

<div class="row">
	<div class="col-md-4">
		<?= $form->field($model, 'trial_count')->widget(TouchSpin::class) ?>
	</div>
	<div class="col-md-8">
		<?= $form->field($model, 'units')->dropDownList(EnumSubscriptionTrialUnits::mapData()) ?>
	</div>
</div>

