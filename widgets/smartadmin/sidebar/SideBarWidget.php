<?php
declare(strict_types = 1);

namespace app\widgets\smartadmin\sidebar;

use Exception;
use yii\base\Widget as YiiBaseWidget;
use yii\helpers\ArrayHelper;
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
		self::prepareItems($this->items);
		return $this->render('main', ['items' => $this->items]);
	}

	/**
	 * @throws Exception
	 */
	private static function prepareItems(array &$items):void {
		$items = array_filter($items, function(array $item) {
			if ((null !== $subItems = ArrayHelper::getValue($item, 'items')) && is_array($subItems)) {
				self::prepareItems($item['items']);
			}
			return (ArrayHelper::getValue($item, 'visible', true));
		}, ARRAY_FILTER_USE_BOTH);
	}
}