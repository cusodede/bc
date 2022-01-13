<?php
declare(strict_types = 1);

/**
 * @var View $this
 */

use yii\bootstrap4\Button;
use app\components\helpers\Html;
use yii\web\View;

?>

<?= Html::link(Button::widget([
	'label' => '<i class="fa fa-radiation"></i> Очистка базы <i class="fa fa-radiation"></i>',
	'encodeLabel' => false,
	'options' => ['class' => 'btn-lg btn btn-danger']
]), ['service/reset'])
?>