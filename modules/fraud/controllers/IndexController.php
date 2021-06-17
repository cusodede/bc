<?php
declare(strict_types = 1);

namespace app\modules\fraud\controllers;

use app\modules\fraud\components\queue\FraudValidatorRepeatJob;
use app\modules\fraud\models\FraudCheckStepSearch;
use yii\web\Controller;
use Yii;

/**
 * Class IndexController
 * @package app\modules\fraud\controllers
 */
class IndexController extends Controller
{
	/**
	 * @return string
	 */
	public function actionList()
	{
		$request = Yii::$app->request;
		if ($request->isPost) {
			if ($repeatValidateId = (int) $request->post('repeat_validate_id')) {
				Yii::$app->queue->push(new FraudValidatorRepeatJob([
					'fraudStepId' => $repeatValidateId
				]));
			}
		}

		$params = $request->queryParams;
		$searchModel = new FraudCheckStepSearch();
		return $this->render('list', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params)
		]);
	}
}
