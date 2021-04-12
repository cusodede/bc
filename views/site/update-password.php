<?php
declare(strict_types = 1);

/**
 * Шаблон страницы смены пароля
 * @var View $this
 * @var ActiveForm $form
 * @var UpdatePasswordForm $model
 */

use app\models\site\UpdatePasswordForm;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Смена пароля';
?>
<div class="panel">
	<div class="panel-body">
		<?php $form = ActiveForm::begin(); ?>
		<div class="form-group">
			Пароль пользователя <b><?= $model->user->login ?></b> просрочен и должен быть изменён.
		</div>
		<div class="form-group">
			<?= $form->field($model, 'oldPassword')->passwordInput(['placeholder' => 'Текущий пароль']) ?>
		</div>
		<div class="form-group">
			<?= $form->field($model, 'newPassword')->passwordInput(['placeholder' => 'Новый пароль']) ?>
		</div>
		<div class="form-group">
			<?= $form->field($model, 'newPasswordRepeat')->passwordInput(['placeholder' => 'Новый пароль ещё раз']) ?>
		</div>
		<?= Html::submitButton('Сменить пароль', ['class' => 'btn btn-primary btn-lg btn-block', 'name' => 'login-button']) ?>
		<?php ActiveForm::end(); ?>
		<?= Html::a('Назад', Yii::$app->homeUrl, ['class' => 'btn-link mar-lft']) ?>
	</div>
</div>