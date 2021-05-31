<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\store\Stores;
use app\models\store\StoresSearch;

/**
 * Class StoresController
 */
class StoresController extends DefaultController {

	public string $modelClass = Stores::class;
	public string $modelSearchClass = StoresSearch::class;

	/**
	 * @inheritDoc
	 */
	public function getViewPath():string {
		return '@app/views/stores';
	}


}