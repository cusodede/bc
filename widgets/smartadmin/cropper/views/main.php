<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var CropperWidget $widget
 */

use app\widgets\smartadmin\cropper\CropperWidget;
use yii\web\View;

?>

<div class="row">
	<div class="col-12">
		<div class="img-container">
			<img id="<?= $widget->imageId ?>" src="/img/theme/avatar-m.png">
		</div>
	</div>
</div>

<div class="row mt-3">
	<div class="col-12">
		<label class="btn btn-primary btn-upload btn-block" for="<?= $widget->cropperUploadInputId ?>">
			<input id="<?= $widget->cropperUploadInputId ?>" type="file" class="sr-only" name="cropper__upload-input" accept="image/*">
			<span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="Загрузка изображения">
				Загрузить фото
			</span>
		</label>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<button id="<?= $widget->cropperCropElementId ?>" type="button" class="btn btn-success btn-block">
			<span class="docs-tooltip" data-toggle="tooltip" title="">
				Установить фото профиля
			</span>
		</button>
	</div>
</div>

