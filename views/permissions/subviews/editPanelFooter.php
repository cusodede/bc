<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Permissions $model
 * @var ActiveForm|string $form
 */
use app\models\sys\permissions\Permissions;
use yii\bootstrap4\ActiveForm;
use app\components\helpers\Html;
use yii\web\View;

?>

<?= Html::submitButton('Сохранить', [
		'class' => $model->isNewRecord?'btn btn-success float-right':'btn btn-primary float-right',
		'form' => is_object($form)?$form->id:$form
	]
) ?>
