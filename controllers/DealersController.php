<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\dealers\Dealers;
use app\models\dealers\DealersSearch;

/**
 * Управление дилерами
 */
class DealersController extends DefaultController {

	public string $modelClass = Dealers::class;
	public string $modelSearchClass = DealersSearch::class;


	/**
	 * @inheritDoc
	 */
//	public function getViewPath():string {
//		return '@app/views/dealers';
//	}
}