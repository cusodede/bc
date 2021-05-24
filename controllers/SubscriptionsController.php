<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\subscriptions\Subscriptions;
use app\models\subscriptions\SubscriptionsSearch;
use pozitronik\core\traits\ControllerTrait;

/**
 * Class SubscriptionsController
 * @package app\controllers
 */
class SubscriptionsController extends DefaultController
{
	use ControllerTrait;

	/**
	 * Поисковая модель подписок
	 * @var string
	 */
	public string $modelSearchClass = SubscriptionsSearch::class;

	/**
	 * Модель подписок
	 * @var string
	 */
	public string $modelClass = Subscriptions::class;

	/**
	 * Переопределим базовую директорию views
	 * @return string
	 */
	public function getViewPath(): string
	{
		return '@app/views/subscriptions';
	}
}