<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Rewards $model
 * @var ActiveForm|string $form
 */

use app\models\reward\Rewards;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

?>

<?= Html::submitButton('Сохранить', [
		'class' => $model->isNewRecord?'btn btn-success pull-right':'btn btn-primary pull-right',
		'form' => is_object($form)?$form->id:$form
	]
) ?>
