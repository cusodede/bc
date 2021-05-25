<?php
declare(strict_types = 1);

namespace app\widgets\smartadmin\sidebar;

use yii\base\Widget as YiiBaseWidget;
use yii\widgets\Menu;

/**
 * Class SideBarWidget
 * @package app\widgets
 *
 * Виджет для отрисовки панели навигации
 */
class SideBarWidget extends YiiBaseWidget {
	/**
	 * @var array список элементов Меню
	 * @see Menu::$items
	 */
	public array $items = [];

	public function run():string {
		return $this->render('main', ['items' => $this->items]);
	}
}