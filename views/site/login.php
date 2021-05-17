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
use app\widgets\smartadmin\checkbox\CheckboxWidget;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Вход';
?>
<div class="row">
	<div class="col-sm-4 col-md-4 col-lg-4 col-xl-4 m-auto">
		<div class="card p-4 rounded-plus bg-faded">
			<?php $form = ActiveForm::begin(); ?>

			<?= $form->field($login, 'login')->textInput(['placeholder' => 'Пожалуйста, введите логин']) ?>
			<?= $form->field($login, 'password')->passwordInput(['placeholder' => 'Пожалуйста, введите пароль']) ?>
			<?= $form->field($login, 'rememberMe')->widget(CheckboxWidget::class) ?>

			<?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-lg btn-block', 'name' => 'login-button']) ?>

			<?php ActiveForm::end(); ?>

			<div class="text-right mt-1">
				<?= Html::a('Восстановление пароля', ['site/restore-password'], ['class' => 'btn-link']) ?>
			</div>
		</div>
	</div>
</div>