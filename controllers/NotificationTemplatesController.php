<?php
declare(strict_types = 1);

namespace app\controllers;

use app\components\web\DefaultController;
use app\models\notification_templates\NotificationTemplates;
use app\models\notification_templates\NotificationTemplatesSearch;

/**
 * Class NotificationTemplatesController
 * @package app\controllers
 */
class NotificationTemplatesController extends DefaultController
{
	public string $modelSearchClass = NotificationTemplatesSearch::class;
	public string $modelClass = NotificationTemplates::class;

	public function getViewPath(): string
	{
		return '@app/views/notification_templates';
	}
}
