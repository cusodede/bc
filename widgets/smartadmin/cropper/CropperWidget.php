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
	 * @var array настройки, используемые для настройки Cropper
	 * @see https://github.com/fengyuanchen/cropperjs
	 */
	public array $pluginOptions = [];

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
		$options = json_encode($this->pluginOptions);

		if ($this->modalId) {
			$jsInit = <<<JS
$('$this->modalId').on('shown.bs.modal', function () { 
    const image = $('#cropper__user-logo_img');
    if (image.data('cropper') === undefined) {
        image.cropper($options);
    } 
});
JS;
			$this->view->registerJs($jsInit);
		} else {
			$this->view->registerJs("$('#cropper__user-logo_img').cropper({$options});");
		}

		return $this->render('main');
	}
}