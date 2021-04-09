<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 */

use app\models\sys\users\Users;
use yii\bootstrap\Modal;
use yii\web\View;

$this->title = "Профиль пользователя {$model->username}";
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Modal::begin([
	'id' => "{$model->formName()}-modal-profile-{$model->id}",
	'size' => Modal::SIZE_LARGE,
	'clientOptions' => [
		'backdrop' => true
	],
	'options' => [
		'class' => 'modal-dialog-large',
	]
]); ?>
<?= $this->render('../profile', [
	'model' => $model
]) ?>

<?php Modal::end(); ?>