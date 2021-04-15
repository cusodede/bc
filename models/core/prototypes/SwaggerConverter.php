<?php
declare(strict_types = 1);

namespace app\models\core\prototypes;

use yii\base\Model;

/**
 * Class SwaggerConverter
 * @property-write PHPDocParser[] $parsers
 */
class SwaggerConverter extends Model {
	public $parsers = [];

	/*Пока я не изучил спецификации, делаю только для примера*/
	/**
	 * @return string[]
	 */
	private function getDefinitions():array {
		$result = [];
		$required = [];
		foreach ($this->parsers as $docParser) {
			if ($docParser->required) $required[] = "\"{$docParser->name}\"";
		}
		if ([] !== $required) {
			$requiredNames = implode(", ", $required);
			$result[] = "@SWG\Definition(required={{$requiredNames}})";
		}
		return $result;
	}

	/**
	 * @return string[]
	 */
	private function getProperties():array {
		$result = [];
		foreach ($this->parsers as $docParser) {
			$result[] = "@SWG\Property(property=\"{$docParser->name}\", type=\"{$docParser->type}\")";
		}
		return $result;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return implode("\n", $this->getDefinitions())."\n\n".implode("\n", $this->getProperties());
	}

}