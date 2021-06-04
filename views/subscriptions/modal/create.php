<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Products $product
 * @var Subscriptions $subscription
 */

use pozitronik\widgets\BadgeWidget;
use yii\bootstrap4\Modal;
use yii\web\View;
use yii\widgets\ActiveForm;
use app\models\products\Products;
use app\models\subscriptions\Subscriptions;

$modelName = $subscription->formName();
?>
<?php Modal::begin([
	'id' => "{$modelName}-modal-create-new",
	'size' => Modal::SIZE_LARGE,
	'title' => BadgeWidget::widget([
		'items' => $subscription,
		'subItem' => 'id'
	]),
	'footer' => $this->render('../subviews/editPanelFooter', [
		'model' => $subscription,
		'form' => "{$modelName}-modal-create"
	]),
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?php $form = ActiveForm::begin(['id' => "{$modelName}-modal-create", 'enableAjaxValidation' => true, 'validateOnChange' => false, 'validateOnBlur' => false]) ?>
<?= $this->render('../subviews/editPanelBody', compact('subscription', 'form', 'product')) ?>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>