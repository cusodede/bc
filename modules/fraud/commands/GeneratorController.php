<?php

namespace app\modules\fraud\commands;

use app\models\product\ProductOrder;
use app\modules\fraud\components\behaviours\ProductOrderSimcardAsyncBehaviour;
use app\modules\fraud\models\FraudCheckStep;
use yii\console\Controller;
use yii\db\Exception;

/**
 * Class GeneratorController
 * @package app\modules\fraud\commands
 */
class GeneratorController extends Controller
{
	/**
	 * @throws Exception
	 */
	public function actionNewSteps()
	{
		FraudCheckStep::deleteAll();

		$behaviour = new ProductOrderSimcardAsyncBehaviour();

		(new FraudCheckStep())->addNewSteps(array_map(static function (string $validatorClass){
			return FraudCheckStep::newStep(random_int(1, 100), ProductOrder::class, $validatorClass);
		}, $behaviour->validators));
	}
}
