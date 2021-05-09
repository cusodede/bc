<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\prototypes\merch\Merch;
use app\models\prototypes\merch\MerchSearch;
use pozitronik\core\traits\ControllerTrait;

/**
 * Class MerchController
 * Управление товарами (админка)
 */
class MerchController extends DefaultController {
	use ControllerTrait;

	public string $modelClass = Merch::class;
	public string $modelSearchClass = MerchSearch::class;

}