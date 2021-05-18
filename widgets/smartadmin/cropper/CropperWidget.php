<?php
declare(strict_types = 1);

namespace app\widgets\smartadmin\cropper;

use yii\base\Widget as YiiBaseWidget;

/**
 * Class CropperWidget
 * @package app\widgets\smartadmin\cropper
 */
class CropperWidget extends YiiBaseWidget
{
	/**
	 * @var array настройки, используемые для инициализации Cropper
	 * @see https://github.com/fengyuanchen/cropperjs
	 */
	public array $pluginOptions = [];
	/**
	 * @var string id <img> для подгрузки фото в кроппер
	 */
	public string $imageId = 'cropper__user-logo_img';
	/**
	 * @var string id файлового input'а
	 */
	public string $cropperUploadInputId = 'cropper__upload-input';
	/**
	 * @var string id элемента, который будет триггерить кроппинг изображения и его отправку на сервак
	 */
	public string $cropperCropElementId = 'cropper__crop';
	/**
	 * @var string|null ID модалки, на случай если кроппер завернут в неё.
	 * Требуется для корректной инициализации кроппера.
	 */
	public ?string $modalId;

	public function init(): void
	{
		parent::init();
		CropperWidgetAsset::register($this->view);
	}

	public function run(): string
	{
		$options = json_encode([
			'imageId' => "#$this->imageId",
			'modalId' => $this->modalId ? "#$this->modalId" : null,
			'cropperUploadInputId' => "#$this->cropperUploadInputId",
			'cropperCropElementId' => "#$this->cropperCropElementId",
			'pluginOptions' => $this->pluginOptions
		]);

		$this->view->registerJs("cropperInitConfig.init({$options})");

		return $this->render('main', ['widget' => $this]);
	}
}