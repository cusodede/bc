<?php
declare(strict_types = 1);

/**
 * Сообщение об отправленном коде восстановления
 * @var View $this
 * @var ActiveForm $form
 */

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Восстановление пароля';
?>
<div class="panel">
	<div class="panel-body">
		На указанный адрес выслано письмо с инструкциями по восстановлению пароля.
		<?= Html::a('Назад', Yii::$app->homeUrl, ['class' => 'btn-link mar-lft']) ?>
	</div>
</div>