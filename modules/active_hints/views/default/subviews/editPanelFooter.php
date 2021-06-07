<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveStorage $model
 * @var ActiveForm|string $form
 */
use app\modules\active_hints\models\ActiveStorage;
use kartik\form\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

?>

<?= Html::submitButton('Сохранить', [
		'class' => $model->isNewRecord?'btn btn-success float-right':'btn btn-primary float-right',
		'form' => is_object($form)?$form->id:$form
	]
) ?>
