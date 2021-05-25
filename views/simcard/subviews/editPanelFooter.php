<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var SimCard $model
 * @var ActiveForm|string $form
 */

use kartik\form\ActiveForm;
use app\models\product\SimCard;
use yii\bootstrap4\Html;
use yii\web\View;

?>

<?= Html::submitButton('Сохранить', [
		'class' => $model->isNewRecord?'btn btn-success pull-right':'btn btn-primary pull-right',
		'form' => is_object($form)?$form->id:$form
	]
) ?>
