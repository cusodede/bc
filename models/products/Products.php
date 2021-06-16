<?php
declare(strict_types = 1);

namespace app\models\products;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class Products
 * @property-read int $id
 * @property-read string $name
 * @property-read string $class
 */
class Products extends Model {
	public ?int $id = null;
	public ?string $name = null;
	public ?string $class = null;

	public array $config = [];

	/**
	 * @return self[]
	 */
	public static function all():array {
		$config = ArrayHelper::getValue(Yii::$app, 'params.productsConfig', []);
		$result = [];
		foreach ($config as $id => $attributes) {
			$result[] = new self([
				'id' => $id,
				'name' => ArrayHelper::getValue($attributes, 'name', new InvalidConfigException('name parameter required')),
				'class' => ArrayHelper::getValue($attributes, 'class', new InvalidConfigException('class parameter required')),
			]);
		}
		return $result;

	}

	public function init() {
		parent::init();
	}

}