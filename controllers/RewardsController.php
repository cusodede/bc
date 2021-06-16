<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\reward\Rewards;
use app\models\reward\RewardsSearch;
use app\models\sys\permissions\filters\PermissionFilter;
use yii\helpers\ArrayHelper;

/**
 * Class RewardsController
 */
class RewardsController extends DefaultController {

	public string $modelClass = Rewards::class;
	public string $modelSearchClass = RewardsSearch::class;

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return ArrayHelper::merge(parent::behaviors(), [
			'access' => [
				'class' => PermissionFilter::class
			]
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function getViewPath():string {
		return '@app/views/rewards';
	}
}