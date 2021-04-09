<?php
declare(strict_types = 1);

namespace app\models\sys\permissions\filters;

use app\models\sys\users\CurrentUserHelper;
use yii\base\Action;
use yii\base\ActionFilter;

/**
 * Class PermissionFilter
 */
class PermissionFilter extends ActionFilter {

	/**
	 * @param Action $action
	 * @return bool
	 */
	public function beforeAction($action) {
		return CurrentUserHelper::model()->hasActionPermission($action);
	}

}