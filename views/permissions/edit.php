<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Permissions $model
 */

use app\models\sys\permissions\Permissions;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>
<?= $this->render('subviews/editPanel', [
	'model' => $model,
	'form' => $form
]) ?>
<?= $this->render('subviews/editFooter', [
	'model' => $model,
	'form' => $form
]) ?>
<div class="clearfix"></div>
<?php ActiveForm::end(); ?>
