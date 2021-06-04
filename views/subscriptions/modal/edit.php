<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */
declare(strict_types = 1);

/**
 * @var View $this
 * @var Products $product
 * @var Subscriptions $subscription
 */
use pozitronik\widgets\BadgeWidget;
use yii\bootstrap4\Modal;
use yii\web\View;
use kartik\form\ActiveForm;
use app\models\products\Products;
use app\models\subscriptions\Subscriptions;

$modelName = $subscription->formName();
?>
<?php
Modal::begin([
	'id' => "{$modelName}-modal-edit-{$subscription->id}",
	'size' => Modal::SIZE_LARGE,
	'title' => BadgeWidget::widget([
		'items' => $subscription,
		'subItem' => 'product.name'
	]),
	'footer' => $this->render('../subviews/editPanelFooter', [
		'model' => $subscription,
		'form' => "{$modelName}-modal-edit"
	]),
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?php $form = ActiveForm::begin([
		'id' => "{$modelName}-modal-edit",
		'enableAjaxValidation' => true,
		'validateOnChange' => false,
		'validateOnBlur' => false,
	]) ?>
<?= $this->render('../subviews/editPanelBody', compact('subscription', 'form', 'product')) ?>
<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>