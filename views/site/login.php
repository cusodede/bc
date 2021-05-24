<?php
declare(strict_types = 1);

/**
 * Шаблон страницы авторизации
 * @var View $this
 * @var ActiveForm $form
 * @var LoginForm $login
 * @var null|string $from Опциональный ключ перехода
 */

use app\models\site\LoginForm;
use yii\web\View;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Вход';
?>
<div class="panel">
	<div class="panel-body">
		<?php $form = ActiveForm::begin(); ?>
		<div class="form-group">
			<?= $form->field($login, 'login')->textInput(['placeholder' => 'Пожалуйста, введите логин']) ?>
		</div>
		<div class="form-group">
			<?= $form->field($login, 'password')->passwordInput(['placeholder' => 'Пожалуйста, введите пароль']) ?>
		</div>
		<div class="form-group">
			<?= $form->field($login, 'rememberMe')->checkbox() ?>
		</div>
		<?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-lg btn-block', 'name' => 'login-button']) ?>
		<?php ActiveForm::end(); ?>
		<?= Html::a('Восстановление пароля', ['site/restore-password'], ['class' => 'btn-link mar-rgt']) ?>
		<?= Html::a('Регистрация', ['site/register'], ['class' => 'btn-link mar-lft']) ?>
	</div>
</div>