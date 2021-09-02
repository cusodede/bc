<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\contracts\Contracts;
use app\models\contracts\ContractsSearch;

/**
 * Class ContractsController
 * @package app\controllers
 */
class ContractsController extends DefaultController
{
	public string $modelSearchClass = ContractsSearch::class;
	public string $modelClass = Contracts::class;

	public function getViewPath(): string
	{
		return '@app/views/contracts';
	}
}
