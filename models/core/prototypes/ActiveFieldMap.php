<?php
declare(strict_types = 1);

namespace app\models\core\prototypes;

use pozitronik\helpers\ReflectionHelper;
use ReflectionException as ReflectionExceptionAlias;
use yii\base\UnknownClassException;
use yii\db\ActiveRecord;
use yii\widgets\InputWidget;

/**
 * Class ActiveFieldMap
 * Генерирует соответствия ActiveField-виджетов по типам данных для создания дефолтных редакторов
 */
class ActiveFieldMap extends InputWidget {

	/**
	 * @param array $options
	 * @return string
	 * @throws ReflectionExceptionAlias
	 * @throws UnknownClassException
	 */
	public function run() {
		if (ReflectionHelper::IsInSubclassOf(ReflectionHelper::New($this->model), [ActiveRecord::class])) {
			$type = $this->model->getTableSchema()->columns[$this->attribute]->type;

		}
		return $this->renderInputHtml($type);

	}

}