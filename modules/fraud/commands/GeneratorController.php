<?php

namespace app\modules\fraud\commands;

use app\models\product\ProductOrder;
use app\modules\fraud\components\behaviours\ProductOrderSimcardAsyncBehaviour;
use app\modules\fraud\models\FraudCheckStep;
use yii\console\Controller;
use Yii;

class GeneratorController extends Controller
{
	public function actionNewSteps()
	{
		$insertRows = array_map(function ($class) {
			$step = FraudCheckStep::newStep(rand(1, 100), ProductOrder::class, $class);
			return array_values($step->toArray());
		}, (new ProductOrderSimcardAsyncBehaviour())->validators);

		Yii::$app->db->createCommand()->batchInsert(FraudCheckStep::tableName(),
			['entity_id', 'entity_class', 'fraud_validator', 'status', 'created_at', 'updated_at'],
			$insertRows
		)->execute();
	}
}