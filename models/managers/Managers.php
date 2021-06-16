<?php
declare(strict_types = 1);

namespace app\models\managers;

use app\controllers\ManagersController;
use app\models\managers\active_record\ManagersAR;
use app\models\traits\CreateAccessTrait;

/**
 * Class Managers
 * Конкретный менеджер
 *
 * @property string $urlToEntity
 */
class Managers extends ManagersAR {
	use CreateAccessTrait;

	public const RUS_CLASS_NAME = 'Менеджер';

	/**
	 * URL для нахождения менеджера по ID
	 * @return string
	 */
	public function getUrlToEntity():string {
		return ManagersController::to('index', ['ManagersSearch[id]' => $this->id], true);
	}
}