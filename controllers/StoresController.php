<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\prototypes\DefaultController;
use app\models\prototypes\seller\Stores;
use app\models\prototypes\seller\StoresSearch;
use pozitronik\core\traits\ControllerTrait;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class SimCardController
 */
class StoresController extends DefaultController {
	use ControllerTrait;

	public string $modelClass = Stores::class;
	public string $modelSearchClass = StoresSearch::class;

	/**
	 * @inheritDoc
	 */
	public function getViewPath():string {
		return '@app/views/stores';
	}

	/**
	 * @return string
	 * @throws InvalidConfigException
	 * @noinspection PhpPossiblePolymorphicInvocationInspection
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new StoresSearch();

		/** @noinspection PhpUndefinedMethodInspection */
		return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $searchModel->search($params),
				'controller' => $this,
				'modelName' => (new Stores())->formName()
			]
		);
	}

}