<?php
declare(strict_types = 1);

/**
 * @var string|null $modalId
 */
use app\widgets\smartadmin\cropper\CropperWidget;

?>

<?= CropperWidget::widget([
	'modalId' => $modalId ?? null,
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
