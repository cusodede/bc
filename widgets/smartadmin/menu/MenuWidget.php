<?php
declare(strict_types = 1);

namespace app\widgets\smartadmin\menu;

use yii\helpers\ArrayHelper;
use yii\bootstrap4\Html;
use yii\widgets\Menu as YiiMenuWidget;

/**
 * Class MenuWidget
 * @package app\widgets\smartadmin\menu
 *
 * Виджет для отрисовки меню
 */
class MenuWidget extends YiiMenuWidget {
	/**
	 * @inheritdoc
	 */
	public function init():void {
		parent::init();

		$this->linkTemplate = '<a href="{url}" data-filter-tags="{tags}">{icon}<span class="nav-link-text">{label}</span></a>';
	}

	/**
	 * @inheritdoc
	 */
	protected function renderItem($item):string {
		$render = parent::renderItem($item);

		$icon = (null === $itemClass = ArrayHelper::getValue($item, 'iconClass'))?'':Html::tag('i', '', ['class' => "fal {$itemClass}"]);

		$tags = mb_strtolower($item['label']);

		return strtr($render, ['{icon}' => $icon, '{tags}' => $tags]);
	}
}