<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use kartik\form\ActiveForm;
use pozitronik\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\View;
use kartik\select2\Select2;
use app\models\ref_subscription_categories\active_record\RefSubscriptionCategories;
use app\models\products\Products;

?>

<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'product_id')->widget(Select2::class, [
			'data' => ArrayHelper::map(Products::find()->active()->all(), 'id', 'name'),
			'pluginOptions' => [
				'multiple' => false,
				'allowClear' => true,
				'placeholder' => 'Выберите продукт',
				'tags' => true
			]
		]) ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?= $form->field($model, 'category_id')->widget(Select2::class, [
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

