<?php
declare(strict_types = 1);

/**
 * @var View $this
 */

use yii\web\View;

?>

<div class="row">
	<div class="col-12">
		<div class="img-container">
			<img src="/img/theme/avatar-m.png" id="cropper__user-logo_img" style="width: 100%; display: block">
		</div>
	</div>
</div>

<div class="row mt-3">
	<div class="col-12">
		<label class="btn btn-primary btn-upload btn-block" for="cropper__upload-input">
			<input type="file" class="sr-only" id="cropper__upload-input" name="cropper__upload-input" accept="image/*">
			<span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="Загрузка изображения">
				Загрузить фото
			</span>
		</label>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<button type="button" class="btn btn-success btn-block" id="cropper__crop">
			<span class="docs-tooltip" data-toggle="tooltip" title="">
				Установить фото профиля
			</span>
		</button>
	</div>
</div>

