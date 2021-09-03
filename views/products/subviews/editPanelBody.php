<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use app\models\revshare_rates\RevShareRates;
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
<?= $form->field($model, 'ext_description')->widget(MarkdownEditor::class, [
	'showExport' => false,
	'footerMessage' => false,
	'toolbar' => [
		[
			'buttons' => [
				MarkdownEditor::BTN_BOLD => ['icon' => 'bold', 'title' => 'Полужирный'],
				MarkdownEditor::BTN_ITALIC => ['icon' => 'italic', 'title' => 'Курсив'],
				MarkdownEditor::BTN_LINK => ['icon' => 'link', 'title' => 'Ссылка'],
				MarkdownEditor::BTN_INDENT_L => ['icon' => 'indent', 'title' => 'Увеличить отступ'],
				MarkdownEditor::BTN_INDENT_R => ['icon' => 'outdent', 'title' => 'Уменьшить отступ'],
				MarkdownEditor::BTN_UL => ['icon' => 'list', 'title' => 'Маркированный список'],
				MarkdownEditor::BTN_OL => ['icon' => 'list-alt', 'title' => 'Нумерованный список'],
				MarkdownEditor::BTN_HR => ['icon' => 'minus', 'title' => 'Горизонтальная линия']
			]
		]
	]
]) ?>
<?= $form->field($model, 'storyLogo')->widget(FileInputWidget::class, [
	'allowDownload' => false,
	'allowVersions' => false
]) ?>

