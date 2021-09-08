<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use app\models\notification_templates\EnumNotificationTemplatesType;
use kartik\form\ActiveForm;
use kartik\markdown\MarkdownEditor;
use yii\base\Model;
use yii\web\View;

?>

<?= $form->field($model, 'type')->dropDownList(EnumNotificationTemplatesType::mapData()) ?>
<?= $form->field($model, 'subject')->textInput() ?>
<?= $form->field($model, 'message_body')->widget(MarkdownEditor::class, [
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
