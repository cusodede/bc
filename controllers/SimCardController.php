<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\product\SimCard;
use app\models\product\SimCardSearch;
use app\models\sys\permissions\filters\PermissionFilter;
use yii\helpers\ArrayHelper;

/**
 * Class SimCardController
 */
class SimCardController extends DefaultController {

	public string $modelClass = SimCard::class;
	public string $modelSearchClass = SimCardSearch::class;

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
		return '@app/views/simcard';
	}

	/**
	 * Продажа карты
	 * @param int $id
	 */
	public function actionSell(int $id):void {
		//todo
	}
}