<?php
declare(strict_types = 1);

namespace app\components\db;

use yii\db\ActiveQuery as YiiActiveQuery;
use yii\db\ActiveRecord;

/**
 * Trait ActiveQueryTrait
 * Каст расширения запросов
 */
class ActiveQuery extends YiiActiveQuery {

	/**
	 * Селектор для флага "deleted", если он присутствует в таблице
	 * @example ActiveRecord::find()->active()->all()
	 * @param bool $deleted
	 * @return $this
	 */
	public function active(bool $deleted = false):self {
		/** @var ActiveRecord $class */
		$class = new $this->modelClass();//Хак для определения вызывающего трейт класса (для определения имени связанной таблицы)
		$tableName = $class::tableName();
		return $class->hasAttribute('deleted')?$this->andOnCondition([$tableName.'.deleted' => $deleted]):$this;
	}
}