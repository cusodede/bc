<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\revshare_rates\RevShareRates;
use app\models\revshare_rates\RevShareRatesSearch;

/**
 * Class RevShareController
 * @package app\controllers
 */
class RevShareController extends DefaultController
{
	public string $modelSearchClass = RevShareRatesSearch::class;
	public string $modelClass = RevShareRates::class;

	/**
	 * @inheritDoc
	 */
	public function getViewPath(): string
	{
		return '@app/views/revshare_rates';
	}
}
