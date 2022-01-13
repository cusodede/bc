<?php
declare(strict_types = 1);

namespace app\components\widgets;

use pozitronik\helpers\ReflectionHelper;
use ReflectionClass;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;

/**
 * Class ActiveFieldMap
 * Генерирует соответствия ActiveField-виджетов по типам данных для создания дефолтных редакторов
 * @property ActiveRecord $model
 */
class ActiveFieldMap extends InputWidget {

	/**
	 * @inheritDoc
	 */
	public function run():string {
		if (ReflectionHelper::IsInSubclassOf(ReflectionHelper::New($this->model), [ActiveRecord::class])) {

			$type = ArrayHelper::getValue($this->model::getTableSchema(), "columns.{$this->attribute}.type", 'string');
			switch ($type) {
				default:
				case 'string':
					return (string)$this->field->textInput();
				case 'integer':
					return (string)$this->field->textInput(['type' => 'number']);
				case 'tinyint':
					return (string)$this->field->checkbox();
				case 'datetime':
					return (string)$this->field->textInput(['type' => 'datetime-local']);//не работает в fx, будет просто текстовое поле
			}

		}
		throw new InvalidConfigException("Expected ActiveRecord, got ".(new ReflectionClass($this->model))->name);
	}

}