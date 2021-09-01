<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use app\models\products\Products;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use pozitronik\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\View;

?>

<?= $form->field($model, 'description')->textInput() ?>
<?= $form->field($model, 'calc_formula')->textInput() ?>
<?= $form->field($model, 'value')->textInput() ?>
<?= $form->field($model, 'product_id')->widget(Select2::class, [
	'data' => ArrayHelper::map(Products::find()->active()->all(), 'id', 'name'),
	'pluginOptions' => [
		'placeholder' => 'Выберите продукт',
		'allowClear' => true,
		'tags' => true
	]
]) ?>
