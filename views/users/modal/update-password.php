<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 */

use app\models\sys\users\Users;
use yii\bootstrap\Modal;
use yii\web\View;

?>

<?php Modal::begin([
	'id' => "{$model->formName()}-modal-update-password-{$model->id}",
	'size' => Modal::SIZE_LARGE,
	'clientOptions' => [
		'backdrop' => true
	],
	'footer' => $this->render('../subviews/editPanelFooter', [
		'model' => $model,
		'form' => "{$model->formName()}-modal-update-password"
	]),
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?= $this->render('../update-password', [
	'model' => $model
]) ?>

<?php Modal::end(); ?>