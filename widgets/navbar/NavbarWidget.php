<?php
declare(strict_types = 1);

namespace app\widgets\navbar;

use app\models\sys\users\Users;
use yii\base\Widget;

/**
 * Class NavbarWidget
 */
class NavbarWidget extends Widget {
	public $user;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init():void {
		parent::init();
		NavbarWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {

		return $this->render('navbar',[
			'user' => $this->user?:new Users()
		]);
	}
}
