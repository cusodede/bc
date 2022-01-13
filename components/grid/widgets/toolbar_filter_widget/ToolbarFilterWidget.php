<?php
declare(strict_types = 1);

namespace app\components\grid\widgets\toolbar_filter_widget;

use yii\base\Widget;

/**
 * Class ToolbarFilterWidget
 */
class ToolbarFilterWidget extends Widget {
	public string $content = '';
	public string $label = '';

	/**
	 * @inheritDoc
	 */
	public function init():void {
		parent::init();
		ToolbarFilterWidgetAssets::register($this->view);
	}

	/**
	 * @inheritDoc
	 */
	public function run():string {
		return $this->render('toolbar_widget', [
			'label' => $this->label,
			'content' => $this->content
		]);
	}

}