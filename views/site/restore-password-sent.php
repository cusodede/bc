<?php
declare(strict_types = 1);

/**
 * Сообщение об отправленном коде восстановления
 * @var View $this
 * @var ActiveForm $form
 */

use pozitronik\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Восстановление пароля';
?>
<div class="row">
	<div class="col-sm-8 col-md-8 col-lg-8 col-xl-8 m-auto">
		<div class="card p-4 rounded-plus bg-faded">
			На указанный адрес выслано письмо с инструкциями по восстановлению пароля.
			<div class="text-right mt-1">
				<?= Html::a('Назад', ArrayHelper::getValue(Yii::$app->params, 'user.loginpage', ['site/login']), ['class' => 'btn-link']) ?>
			</div>
		</div>
	</div>
</div>