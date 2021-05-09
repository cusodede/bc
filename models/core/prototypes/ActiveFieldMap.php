<?php
declare(strict_types = 1);

namespace app\models\core\prototypes;

use pozitronik\helpers\ReflectionHelper;
use ReflectionException as ReflectionExceptionAlias;
use yii\base\UnknownClassException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;

/**
 * Class ActiveFieldMap
 * Генерирует соответствия ActiveField-виджетов по типам данных для создания дефолтных редакторов
 */
class ActiveFieldMap extends InputWidget {

	/**
	 * @var ActiveRecord
	 */
	public $model;

	/**
	 * @param array $options
	 * @return string
	 * @throws ReflectionExceptionAlias
	 * @throws UnknownClassException
	 */
	public function run() {
		if (ReflectionHelper::IsInSubclassOf(ReflectionHelper::New($this->model), [ActiveRecord::class])) {
			$type = ArrayHelper::getValue($this->model::getTableSchema(), "columns.{$this->attribute}.type", 'string');
			switch ($type) {
				default:
				case 'string':
					return $this->renderInputHtml('text');
				case 'integer':
					return $this->renderInputHtml('number');
				case 'tinyint':
					return $this->renderInputHtml('checkbox');
				case 'datetime':
					return $this->renderInputHtml('datetime-local');//не работает в fx, будет просто текстовое поле
			}

		}

	}

}