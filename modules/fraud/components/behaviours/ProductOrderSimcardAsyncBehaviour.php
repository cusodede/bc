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
		$insertRows = array_map(function ($class) use ($event) {
			$step = FraudCheckStep::newStep($event->sender->id, get_class($event->sender), $class);
			return array_values($step->toArray());
		}, $this->validators);

		$insertedRows = Yii::$app->db->createCommand()->batchInsert(FraudCheckStep::tableName(),
			['entity_id', 'entity_class', 'fraud_validator', 'status', 'created_at', 'updated_at'],
			$insertRows
		)->execute();
		if ($insertedRows !== count($insertRows)) {
			throw new DomainException("Не получилось вставить все записи");
		}

		foreach ($this->validators as $validatorClass) {
			Yii::$app->queue->push(new FraudValidatorJob([
				'validatorClass' => $validatorClass,
				'productOrderId' => $event->sender->id
			]));
		}
	}
}