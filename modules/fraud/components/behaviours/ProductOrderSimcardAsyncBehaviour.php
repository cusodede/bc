<?php
declare(strict_types = 1);

namespace app\modules\fraud\components\behaviours;

use app\modules\fraud\components\queue\FraudValidatorJob;
use app\modules\fraud\components\validators\orders\simcard\IsActiveSimcardValidator;
use app\modules\fraud\models\FraudCheckStep;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;
use yii\db\AfterSaveEvent;
use yii\db\Exception;
use DomainException;

/**
 * Class ProductOrderSimcardAsyncBehaviour
 * @package app\modules\fraud\components\behaviours
 */
class ProductOrderSimcardAsyncBehaviour extends Behavior
{
	public array $validators = [
		IsActiveSimcardValidator::class
	];

	/**
	 * @inheritDoc
	 */
	public function events():array {
		return [
			ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
		];
	}

	/**
	 * @param AfterSaveEvent $event
	 * @throws Exception
	 */
	public function afterInsert(AfterSaveEvent $event)
	{
		(new FraudCheckStep())->addNewSteps(array_map(function ($class) use ($event) {
			return FraudCheckStep::newStep($event->sender->id, get_class($event->sender), $class);
		}, $this->validators));

		foreach ($this->validators as $validatorClass) {
			Yii::$app->queue->push(new FraudValidatorJob([
				'validatorClass' => $validatorClass,
				'entityId' => $event->sender->id
			]));
		}
	}
}