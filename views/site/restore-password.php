<?php
declare(strict_types = 1);

/**
 * Шаблон страницы восстановления пароля
 * @var View $this
 * @var ActiveForm $form
 * @var RestorePasswordForm $model
 */

use app\models\site\RestorePasswordForm;
use yii\web\View;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Восстановление пароля';
?>
<div class="panel">
	<div class="panel-body">
		<?php $form = ActiveForm::begin(); ?>
		<div class="form-group">
			<?= $form->field($model, 'email')->passwordInput(['placeholder' => 'Почтовый адрес, указанный при регистрации']) ?>
		</div>
		<?= Html::submitButton('Восстановить пароль', ['class' => 'btn btn-primary btn-lg btn-block', 'name' => 'login-button']) ?>
		<?php ActiveForm::end(); ?>
		<?= Html::a('Назад', Yii::$app->homeUrl, ['class' => 'btn-link mar-lft']) ?>
	</div>
</div>