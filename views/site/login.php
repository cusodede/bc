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
<div class="row">
	<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 m-auto">
		<div class="card p-4 rounded-plus bg-faded">
			<?php $form = ActiveForm::begin(); ?>

			<?= $form->field($login, 'login')->textInput(['placeholder' => 'Пожалуйста, введите логин']) ?>
			<?= $form->field($login, 'password')->passwordInput(['placeholder' => 'Пожалуйста, введите пароль']) ?>
			<?= $form->field($login, 'rememberMe')->checkbox() ?>

			<?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-lg btn-block', 'name' => 'login-button']) ?>

			<?php ActiveForm::end(); ?>

			<div class="mt-1 ">
				<?= Html::a('Восстановление пароля', ['site/restore-password'], ['class' => 'btn-link fa-pull-left text-left']) ?>
				<?= Html::a('Регистрация', ['site/register'], ['class' => 'btn-link fa-pull-right text-right']) ?>
			</div>
		</div>
	</div>
</div>