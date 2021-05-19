<?php
declare(strict_types = 1);

use app\widgets\smartadmin\cropper\CropperWidget;
use yii\bootstrap4\Modal;

?>

<?php Modal::begin(['id' => 'cropperModal', 'title' => 'Фото профиля']) ?>

<?= CropperWidget::widget([
	'modalId' => $modalId??null,
	'pluginOptions' => [
		'aspectRatio' => 1,
		'viewMode' => 2,
		'zoomable' => false,
		'restore' => false,
		'checkCrossOrigin' => false,
		'checkOrientation' => false,
		'highlight' => false,
		'movable' => false,
		'rotatable' => false,
		'scalable' => false,
		'toggleDragModeOnDblclick' => false,
	]
]) ?>

<?php Modal::end();
