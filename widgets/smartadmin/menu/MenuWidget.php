<?php
declare(strict_types=1);

namespace app\widgets\smartadmin\menu;

use yii\helpers\Html;
use yii\widgets\Menu as YiiMenuWidget;

/**
 * Class MenuWidget
 * @package app\widgets\smartadmin\menu
 *
 * Виджет для отрисовки меню
 */
class MenuWidget extends YiiMenuWidget
{
	/**
	 * @inheritdoc
	 */
	public function init(): void
	{
		parent::init();

		$this->linkTemplate = '<a href="{url}">{icon}<span class="nav-link-text">{label}</span></a>';
	}

	/**
	 * @inheritdoc
	 */
	protected function renderItem($item): string
	{
		$render = parent::renderItem($item);

		if (isset($item['iconClass'])) {
			$icon = Html::tag('i', '', ['class' => "fal {$item['iconClass']}"]);
		} else {
			$icon = '';
		}

		return strtr($render, ['{icon}' => $icon]);
	}
}