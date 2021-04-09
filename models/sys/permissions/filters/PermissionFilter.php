<?php
declare(strict_types = 1);

namespace app\models\sys\permissions\filters;

use app\models\sys\users\CurrentUserHelper;
use Throwable;
use yii\base\Action;
use yii\base\ActionFilter;

/**
 * Class PermissionFilter
 */
class PermissionFilter extends ActionFilter {

	/**
	 * @param Action $action
	 * @return bool
	 * @throws Throwable
	 */
	public function beforeAction($action):bool {
		return CurrentUserHelper::model()->hasActionPermission($action);
	}

}