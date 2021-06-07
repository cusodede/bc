<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Products $product
 * @var Subscriptions $subscription
 */

use yii\web\View;
use kartik\form\ActiveForm;
use app\models\products\Products;
use app\models\subscriptions\Subscriptions;

?>

<?php $form = ActiveForm::begin(); ?>
<div class="panel">
	<div class="panel-heading">
	</div>
	<div class="panel-body">
		<?= $this->render('subviews/editPanelBody', compact('subscription', 'form', 'product')) ?>
	</div>
	<div class="panel-footer">
		<?= $this->render('subviews/editPanelFooter', ['model' => $subscription, 'form' => $form]) ?>
		<div class="clearfix"></div>
	</div>
</div>
<?php ActiveForm::end(); ?>
