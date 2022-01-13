<?php
declare(strict_types = 1);

namespace app\components\validators;

use pozitronik\helpers\DateHelper;
use yii\validators\Validator;

/**
 * Class TimestampValidator
 * Псевдовалидатор, устанавливающий проверяемому атрибуту метку времени при первом сохранении.
 * Нужен для таблиц, не умеющих в таймстампы. По сути заменяет TimestampBehavior - в правилах это просто удобнее контролировать
 *
 * См. также ActiveRecordTimestamp
 */
class TimestampValidator extends Validator {

	/**
	 * @inheritDoc
	 */
	public function validateAttribute($model, $attribute):void {
		$model->$attribute = DateHelper::lcDate();
	}

}