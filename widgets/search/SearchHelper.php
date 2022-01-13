<?php
declare(strict_types = 1);

namespace app\widgets\search;

use app\components\helpers\TemporaryHelper;
use Exception;
use yii\base\Model;
use yii\base\UnknownPropertyException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class SearchHelper
 */
class SearchHelper {

	public const SEARCH_TYPE_EQUAL = '=';
	public const SEARCH_TYPE_LIKE = 'like';
	public const SEARCH_TYPE_LIKE_BEGINNING = '%like';
	public const SEARCH_TYPE_LIKE_ENDING = 'like%';

	/**
	 * Возвращает список всех аттрибутов поисковой модели, вычисленный через rules()
	 * @param string|Model $modelClass
	 * @return array
	 */
	public static function GetSearchAttributes(string|Model $modelClass):array {
		$model = (is_string($modelClass))
			?new $modelClass()
			:$modelClass;
		$searchFields = [[]];
		foreach ($model->rules() as $rule) {
			$searchFields[] = (array)$rule[0];
		}
		return array_merge(...$searchFields);
	}

	/**
	 * Возвращает значения всех аттрибутов поисковой модели, вычисленный через rules()
	 * @param string|Model $modelClass
	 * @return array
	 */
	public static function GetSearchAttributesValues(string|Model $modelClass):array {
		$model = (is_string($modelClass))
			?new $modelClass()
			:$modelClass;
		$result = [];
		foreach (static::GetSearchAttributes($model) as $attribute) {
			$result[$attribute] = $model->$attribute;
		}
		return $result;
	}

	/**
	 * @param string|Model $modelClass
	 * @return array
	 */
	public static function GetSafeAttributesValues(string|Model $modelClass):array {
		$model = (is_string($modelClass))
			?new $modelClass()
			:$modelClass;
		$result = [];
		foreach ($model->safeAttributes() as $attribute) {
			$result[$attribute] = $model->$attribute;
		}
		return $result;
	}

	/**
	 * @param string|Model $modelClass
	 * @return array
	 * @throws Exception
	 */
	private static function AssumeSearchAttributes(string|Model $modelClass):array {
		$model = (is_string($modelClass))
			?new $modelClass()
			:$modelClass;
		$searchFields = [[]];
		foreach ($model->rules() as $rule) {
			if ('string' === ArrayHelper::getValue($rule, '1')) {
				$searchFields[] = (array)$rule[0];
			}
		}
		return array_merge(...$searchFields);
	}

	/**
	 * @param string $modelClass Имя класса ActiveRecord-модели (FQN), к которой подключается поиск
	 * @param string|null $term Поисковый запрос
	 * @param int $limit Лимит поиска
	 * @param array|null $searchAttributes Массив атрибутов, в которых производим поиск в формате
	 *    [
	 *        'attributeName',
	 *        'attributeName' => 'searchType'
	 *    ]
	 * где searchType - одна из SEARCH_TYPE_* - констант.
	 * Если параметр не задан, атрибуты подхватываются из правил валидации модели (все строковые атрибуты)
	 * @param string $method
	 * @return array
	 * @throws UnknownPropertyException
	 */
	public static function Search(string $modelClass, ?string $term, int $limit = SearchWidget::DEFAULT_LIMIT, ?array $searchAttributes = null, string $method = SearchWidget::DEFAULT_METHOD):array {
		/*В модели можно полностью переопределить поиск*/
		if (method_exists($modelClass, $method)) return $modelClass::$method($term, $limit, $searchAttributes);

		if (null === $searchAttributes) $searchAttributes = self::AssumeSearchAttributes($modelClass);

		/** @var ActiveRecord $modelClass */
		if ((null === $pk = ArrayHelper::getValue($modelClass::primaryKey(), 0))) {
			throw new UnknownPropertyException('Primary key not configured');
		}
		$tableName = $modelClass::tableName();
		$swTermCyr = TemporaryHelper::SwitchKeyboard($term);
		$swTermLat = TemporaryHelper::SwitchKeyboard($term, true);
		$searchQuery = $modelClass::find()->select("{$tableName}.{$pk}");
		foreach ($searchAttributes as $searchRule) {
			if (is_array($searchRule) && isset($searchRule[0], $searchRule[1])) {//attribute, search type
				[$searchAttribute, $searchType] = $searchRule;
			} else {
				$searchAttribute = $searchRule;
				$searchType = "like";
			}
			$searchQuery->addSelect("{$tableName}.{$searchAttribute} as {$searchAttribute}");
			switch ($searchType) {
				case self::SEARCH_TYPE_EQUAL:
					$searchQuery->orWhere(["=", "{$tableName}.{$searchAttribute}", $term]);
					$searchQuery->orWhere(["=", "{$tableName}.{$searchAttribute}", $swTermCyr]);
					$searchQuery->orWhere(["=", "{$tableName}.{$searchAttribute}", $swTermLat]);
				break;
				case self::SEARCH_TYPE_LIKE:
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "%$term%", false]);
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "%$swTermCyr%", false]);
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "%$swTermLat%", false]);
				break;
				case self::SEARCH_TYPE_LIKE_BEGINNING:
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "%$term", false]);
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "%$swTermCyr", false]);
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "%$swTermLat", false]);

				break;
				case self::SEARCH_TYPE_LIKE_ENDING:
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "$term%", false]);
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "$swTermCyr%", false]);
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "$swTermLat%", false]);
				break;
			}
		}

		if (method_exists($searchQuery, 'active')) {
			$searchQuery->active();
		}
		return $searchQuery->distinct()
			->limit($limit)
			->asArray()
			->all();

	}

}