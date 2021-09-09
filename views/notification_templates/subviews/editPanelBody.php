<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $model
 * @var ActiveForm $form
 */

use app\components\PresetMarkdownEditor;
use app\models\notification_templates\EnumNotificationTemplatesType;
use kartik\form\ActiveForm;
use kartik\markdown\MarkdownEditor;
use yii\base\Model;
use yii\web\View;

?>

<?= $form->field($model, 'type')->dropDownList(EnumNotificationTemplatesType::mapData()) ?>
<?= $form->field($model, 'subject')->textInput() ?>
<?= $form->field($model, 'message_body')->widget(MarkdownEditor::class, PresetMarkdownEditor::$presetDefault) ?>
