<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\seller\seller\Sellers;
use app\models\seller\seller\SellersSearch;
use pozitronik\core\traits\ControllerTrait;

/**
 * Class SellersController
 */
class SellersController extends DefaultController {
	use ControllerTrait;

	public string $modelClass = Sellers::class;
	public string $modelSearchClass = SellersSearch::class;

	/**
	 * @inheritDoc
	 */
	public function getViewPath():string {
		return '@app/views/sellers';
	}

}