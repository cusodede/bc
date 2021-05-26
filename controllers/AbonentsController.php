<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\abonents\AbonentsSearch;
use app\models\abonents\Abonents;
use pozitronik\core\traits\ControllerTrait;

/**
 * Class PartnersController
 * @package app\controllers
 */
class AbonentsController extends DefaultController
{
	use ControllerTrait;

	/**
	 * @var string
	 */
	public string $modelSearchClass = AbonentsSearch::class;

	/**
	 * @var string
	 */
	public string $modelClass = Abonents::class;

	public function getViewPath(): string
	{
		return '@app/views/abonents';
	}
}
