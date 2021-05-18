<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\partners\{PartnersSearch, Partners};
use pozitronik\core\traits\ControllerTrait;

/**
 * Class PartnersController
 * @package app\controllers
 */
class PartnersController extends DefaultController
{
	use ControllerTrait;

	public string $modelClass = Partners::class;

	public string $modelSearchClass = PartnersSearch::class;

	public function getViewPath(): string
	{
		return '@app/views/partners';
	}

}