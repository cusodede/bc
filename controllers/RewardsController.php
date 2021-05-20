<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\reward\Reward;
use app\models\reward\RewardSearch;
use pozitronik\core\traits\ControllerTrait;

/**
 * Class RewardsController
 */
class RewardsController extends DefaultController {
	use ControllerTrait;

	public string $modelClass = Reward::class;
	public string $modelSearchClass = RewardSearch::class;

}