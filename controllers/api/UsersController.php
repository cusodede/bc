<?php
declare(strict_types = 1);

namespace app\controllers\api;

use app\models\sys\users\Users;
use yii\rest\ActiveController;

/**
 * Class UserController
 */
class UsersController extends ActiveController {
	public $modelClass = Users::class;
}