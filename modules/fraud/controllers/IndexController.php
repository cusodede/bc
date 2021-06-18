<?php
declare(strict_types = 1);

namespace app\modules\fraud\controllers;

use app\modules\fraud\components\queue\ChangeFraudStepWithRepeatValidateJob;
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
	 * @throws \Throwable
	 */
	public function actionList():string {
		$request = Yii::$app->request;
		$notification = null;

		if ($request->isPost && $repeatValidateId = (int) $request->post('repeat_validate_id')) {
			if ($repeatValidateId > 0) {
				Yii::$app->queue->push(new ChangeFraudStepWithRepeatValidateJob([
					'fraudStepId' => $repeatValidateId
				]));
				$notification = 'Задание на перерасчет фрода успешно добавлено в очередь';
			}
		}

		$params = $request->queryParams;
		$searchModel = new FraudCheckStepSearch();
		return $this->render('list', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params),
			'notification' => $notification
		]);
	}
}
