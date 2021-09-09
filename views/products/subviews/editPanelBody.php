<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use app\components\PresetMarkdownEditor;
use kartik\form\ActiveForm;
use kartik\markdown\MarkdownEditor;
use kartik\select2\Select2;
use pozitronik\filestorage\widgets\file_input\FileInputWidget;
use pozitronik\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\View;
use app\models\partners\Partners;
use app\models\products\EnumProductsPaymentPeriods;

?>

<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'description')->textarea() ?>
<div class="row mb-4">
	<div class="col-sm-6">
		<?= $form->field($model, 'price')->textInput(['type' => 'number']) ?>
	</div>
	<div class="col-sm-6">
		<?= $form->field($model, 'payment_period')->dropDownList(EnumProductsPaymentPeriods::mapData()) ?>
	</div>
</div>
<?= $form->field($model, 'partner_id')->widget(Select2::class, [
	'data' => ArrayHelper::map(Partners::find()->active()->all(), 'id', 'name'),
	'pluginOptions' => [
		'placeholder' => 'Выберите партнера',
		'multiple' => false,
		'allowClear' => true,
		'tags' => true
	]
]) ?>
<?= $form->field($model, 'ext_description')->widget(MarkdownEditor::class, PresetMarkdownEditor::$presetDefault) ?>
<?= $form->field($model, 'storyLogo')->widget(FileInputWidget::class, [
	'allowDownload' => false,
	'allowVersions' => false
]) ?>

