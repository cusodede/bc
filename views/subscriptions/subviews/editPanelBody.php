<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Subscriptions $subscription
 * @var Products $product
 * @var ActiveForm $form
 */

use kartik\form\ActiveForm;
use pozitronik\helpers\ArrayHelper;
use app\models\subscriptions\Subscriptions;
use yii\web\View;
use kartik\select2\Select2;
use app\models\ref_subscription_categories\active_record\RefSubscriptionCategories;
use app\models\products\Products;
use app\models\partners\Partners;
use \kartik\switchinput\SwitchInput;

?>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($product, 'name')->textInput() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($product, 'description')->textarea() ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<?= $form->field($product, 'price')->textInput(['type' => 'number']) ?>
	</div>
	<div class="col-md-6">
		<?= $form->field($product, 'partner_id')->widget(Select2::class, [
			'data' => ArrayHelper::map(Partners::find()->active()->all(), 'id', 'name'),
			'pluginOptions' => [
				'multiple' => false,
				'allowClear' => true,
				'placeholder' => 'Выберите партнера',
				'tags' => true
			]
		]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<?= $form->field($subscription, 'trial')->widget(SwitchInput::class, [
			'pluginOptions' => [
				'onText' => 'Да',
				'offText' => 'Нет',
			]
		]) ?>
	</div>
	<div class="col-md-3">
		<?= $form->field($subscription, 'trial_days_count')->textInput(['type' => 'number']) ?>
	</div>
	<div class="col-md-6">
		<?= $form->field($subscription, 'category_id')->widget(Select2::class, [
			'data' => RefSubscriptionCategories::mapData(),
			'pluginOptions' => [
				'multiple' => false,
				'allowClear' => true,
				'placeholder' => 'Выберите категорию подписки',
				'tags' => true
			]
		]) ?>
	</div>
</div>

