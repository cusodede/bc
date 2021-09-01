<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\refsharing_rates\RevShare;
use app\models\refsharing_rates\RevShareSearch;

/**
 * Class RevShareController
 * @package app\controllers
 */
class RevShareController extends DefaultController
{
	public string $modelSearchClass = RevShareSearch::class;
	public string $modelClass = RevShare::class;

	public function getViewPath(): string
	{
		return '@app/views/refsharing_rates';
	}
}
