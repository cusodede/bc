<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\partners\PartnersSearch;
use app\models\partners\Partners;

/**
 * Class PartnersController
 * @package app\controllers
 */
class PartnersController extends DefaultController
{

	/**
	 * Поисковая модель партнера
	 * @var string
	 */
	public string $modelSearchClass = PartnersSearch::class;

	/**
	 * Модель партнера
	 * @var string
	 */
	public string $modelClass = Partners::class;

	/**
	 * Переопределим базовую директорию views
	 * @return string
	 */
	public function getViewPath(): string
	{
		return '@app/views/partners';
	}
}