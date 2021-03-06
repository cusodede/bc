<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\modules\history\behaviors\HistoryBehavior;
use app\modules\history\HistoryModule;
use app\modules\history\models\active_record\History;
use app\modules\history\models\active_record\HistoryTags;
use pozitronik\helpers\ModuleHelper;
use pozitronik\helpers\ReflectionHelper;
use pozitronik\helpers\ArrayHelper;
use ReflectionException;
use Throwable;
use Yii;
use yii\base\Event;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\UnknownClassException;
use yii\base\UnknownPropertyException;
use yii\console\Application;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class ActiveRecordHistory
 * Занимается сохранением/восстановлением истории изменений для ActiveRecord-классов
 *
 * @property mixed $attributesOld Восстановленные прежние атрибуты
 * @property mixed $attributesNew Восстановленные текущие атрибуты
 * @property HistoryTags|null $relatedHistoryTags Связанная с записью модель тега
 * @property null|string $tag Тег записи
 * @property-read int $eventType Тип произошедшего изменения, @see [[HistoryEvent::$eventType]]
 * @property-read HistoryEventInterface $historyEvent Модель описания события
 * @property-read HistoryEventAction[] $historyEventActions Массив моделей описания изменений, представляющих сохранённое изменение
 *
 * @property-read int $historyLevelCount Количество уровней истории для прогруженной модели
 * @property ActiveRecord|null $loadedModel Прогруженная (если есть возможность) модель указанного в записи класса.
 *
 * @property bool $storeShortClassNames Сохранять короткие/полные имена классов. Если параметр задан в конфиге модуля, то загрузится из конфига
 */
class ActiveRecordHistory extends History {
	/**
	 * @var array|null Хранилка десериализованных старых атрибутов
	 */
	protected ?array $_oldAttributes = null;

	/**
	 * @var array|null Хранилка десериализованных новых атрибутов
	 */
	protected ?array $_newAttributes = null;

	/**
	 * @var null|array the functions used to serialize and unserialize values. Defaults to null, meaning
	 * using the default PHP `serialize()` and `unserialize()` functions. If you want to use some more efficient
	 * serializer (e.g. [igbinary](https://pecl.php.net/package/igbinary)), you may configure this property with
	 * a two-element array. The first element specifies the serialization function, and the second the deserialization
	 * function.
	 */
	public ?array $serializer = null;

	private ?ActiveRecord $_loadedModel = null;
	public bool $storeShortClassNames = false;

	/**
	 * Shorthand to get string identifier of stored class name (short/full class name)
	 * @param Model|null $model
	 * @return string
	 * @throws InvalidConfigException
	 */
	public function getStoredClassName(?Model $model = null):string {
		if (null === $model) $model = $this->loadedModel;
		return $this->storeShortClassNames?$model->formName():get_class($model);
	}

	/**
	 * @param null|ActiveRecord $model Модель события
	 * @param array $oldAttributes Прежние значения
	 * @param array $newAttributes Новые значения
	 * @param ActiveRecord|null $relationModel Опционально: модель события
	 * @param Event|null $event
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public static function push(?ActiveRecord $model, array $oldAttributes, array $newAttributes, ?ActiveRecord $relationModel = null, ?Event $event = null):void {
		$log = new self(['storeShortClassNames' => ArrayHelper::getValue(ModuleHelper::params(HistoryModule::class), "storeShortClassNames", false)]);
		if (get_class(Yii::$app) === Application::class) {
			$log->setAttributes([
				'user' => null,
				'model_class' => null === $model?null:$log->getStoredClassName($model),
				'model_key' => is_numeric($model->primaryKey)?$model->primaryKey:null,//$pKey может быть массивом
				'old_attributes' => $log->serialize($oldAttributes),
				'new_attributes' => $log->serialize($newAttributes),
				'relation_model' => null === $relationModel?null:$log->getStoredClassName($relationModel),
				'event' => $event?->name,
				'scenario' => $model->scenario,
				'delegate' => null,
				'operation_identifier' => "Console operation"
			]);
		} else {
			$log->setAttributes([
				'user' => Yii::$app->user->id,//Предполагается, что фреймворк сконфигурирован с использованием user identity class
				'model_class' => null === $model?null:$log->getStoredClassName($model),
				'model_key' => is_numeric($model->primaryKey)?$model->primaryKey:null,//$pKey может быть массивом
				'old_attributes' => $log->serialize($oldAttributes),
				'new_attributes' => $log->serialize($newAttributes),
				'relation_model' => null === $relationModel?null:$log->getStoredClassName($relationModel),
				'event' => $event?->name,
				'scenario' => $model->scenario,
				'delegate' => self::ensureDelegate(),
				'operation_identifier' => Yii::$app->request->csrfToken
			]);
		}

		$log->save();
	}

	/**
	 * Добавляет указанный тег к истории модели
	 * @param ActiveRecord $model Модель, историю которой тегируем
	 * @param string $tag Тег
	 * @param string|null $operation_identifier Тегируемые изменения (null - последнее изменение)
	 * @return bool True - тег добавлен, false - запись истории не найдена
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public static function addTag(ActiveRecord $model, string $tag = HistoryTags::TAG_CREATED, ?string $operation_identifier = null):bool {
		$log = new self(['storeShortClassNames' => ArrayHelper::getValue(ModuleHelper::params(HistoryModule::class), "storeShortClassNames", false)]);
		if (null === $taggedRecord = self::find()->where([
				'model_class' => $log->getStoredClassName($model),
				'model_key' => is_numeric($model->primaryKey)?$model->primaryKey:null//$pKey может быть массивом
			])->andFilterWhere(['operation_identifier' => $operation_identifier])
				->orderBy(['id' => SORT_DESC])
				->one()) return false;
		/** @var self $taggedRecord */
		$taggedRecord->tag = $tag;
		return true;
	}

	/**
	 * @return int|null
	 */
	private static function ensureDelegate():?int {
		if (method_exists(Yii::$app->user, 'getOriginalUserId')) {
			return Yii::$app->user->getOriginalUserId();
		}
		return null;
	}

	/**
	 * @return string
	 */
	public function getTimestamp():string {
		return $this->at;
	}

	/**
	 * По параметрам, сохранённым в истории, пытается получить экземпляр класса.
	 * ReflectionHelper::LoadClassByName документируется, как [[ReflectionClass|null]], но подразумевается экземпляр ActiveRecord
	 * @return ActiveRecord|null
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 * @noinspection PhpIncompatibleReturnTypeInspection - мы можем конкретизировать тип
	 */
	public function getLoadedModel():?ActiveRecord {
		return $this->_loadedModel??ReflectionHelper::LoadClassByName(self::ExpandClassName($this->model_class), null, false);
	}

	/**
	 * @param ActiveRecord $model
	 */
	public function setLoadedModel(ActiveRecord $model):void {
		$this->_loadedModel = $model;
	}

	/**
	 * @param null|string $key
	 * @param mixed|null $default
	 * @return mixed
	 * @throws Throwable
	 */
	private function getModelRules(?string $key = null, mixed $default = null) {
		$behaviors = $this?->loadedModel?->behaviors()??[];
		$keys = ArrayHelper::array_find_deep($behaviors, HistoryBehavior::class);
		array_pop($keys);
		if (null !== $key) $keys[] = $key;
		return ArrayHelper::getValue($behaviors, implode('.', $keys), $default);
	}

	/**
	 * @return int
	 * @throws Throwable
	 * @noinspection TypeUnsafeComparisonInspection Тут нужно именно нестрогое сравнение
	 */
	public function getEventType():int {
		if (null !== $eventsConfig = $this->getModelRules("events")) {
			/** @var array $eventRule */
			foreach ($eventsConfig as $eventType => $eventRule) {
				foreach ($eventRule as $attribute => $condition) {
					if (is_array($condition)) {
						$oldAssumedValue = ArrayHelper::getValue($condition, 'from');
						$newAssumedValue = ArrayHelper::getValue($condition, 'to');
						if (null !== $oldAssumedValue) {
							$fromCondition = $oldAssumedValue == ArrayHelper::getValue($this->attributesOld, $attribute);//не используем строгое сравнение
						} else $fromCondition = true;

						if (null !== $newAssumedValue) {
							$toCondition = $newAssumedValue == ArrayHelper::getValue($this->attributesNew, $attribute);
						} else $toCondition = true;

						if ($fromCondition && $toCondition) return $eventType;
					} elseif ($condition == ArrayHelper::getValue($this->attributesNew, $attribute)) return $eventType;
				}
			}
		}

		if ([] === $this->attributesOld) return HistoryEvent::EVENT_CREATED;
		if ([] === $this->attributesNew) return HistoryEvent::EVENT_DELETED;
		return HistoryEvent::EVENT_CHANGED;

	}

	/**
	 * Переводит запись из лога в событие истории
	 * @return HistoryEventInterface
	 * @throws Throwable
	 */
	public function getHistoryEvent():HistoryEventInterface {
		$result = new HistoryEvent();

		$result->eventType = $this->eventType;
		$result->eventTime = $this->at;
		$result->objectName = $this->model_class;
		$result->subject = $this->user;
		$result->actions = $this->historyEventActions;

		$result->eventCaption = ArrayHelper::getValue(HistoryEventInterface::EVENT_TYPE_NAMES, $this->eventType);

		$labelsConfig = $this->getModelRules("eventConfig.eventLabels");

		if (ReflectionHelper::is_closure($labelsConfig)) {
			$result->eventCaption = $labelsConfig($result->eventType, $result->eventTypeName);
		} elseif (is_array($labelsConfig)) {
			$result->eventCaption = ArrayHelper::getValue($labelsConfig, $result->eventType, $result->eventTypeName);
		} elseif (null !== $labelsConfig) $result->eventCaption = $labelsConfig;

		$result->actionsFormatter = $this->getModelRules("eventConfig.actionsFormatter");

		return $result;
	}

	/**
	 * Вытаскивает из записи описание изменений атрибутов, конвертируя их в набор HistoryEventAction
	 * @return HistoryEventAction[]
	 * @throws Throwable
	 */
	public function getHistoryEventActions():array {
		$diff = [];

		$labels = null === $this->loadedModel?[]:$this->loadedModel->attributeLabels();

		foreach ($this->attributesOld as $attributeName => $attributeValue) {
			if (isset($this->attributesNew[$attributeName])) {
				$diff[] = new HistoryEventAction([
					'attributeName' => ArrayHelper::getValue($labels, $attributeName, $attributeName),
					'attributeOldValue' => $this->SubstituteAttributeValue($attributeName, $attributeValue),
					'type' => HistoryEventAction::ATTRIBUTE_CHANGED,
					'attributeNewValue' => $this->SubstituteAttributeValue($attributeName, $this->attributesNew[$attributeName])
				]);
			} else {
				$diff[] = new HistoryEventAction([
					'attributeName' => ArrayHelper::getValue($labels, $attributeName, $attributeName),
					'attributeOldValue' => $this->SubstituteAttributeValue($attributeName, $attributeValue),
					'type' => HistoryEventAction::ATTRIBUTE_DELETED
				]);

			}
		}
		$e = array_diff_key($this->attributesNew, $this->attributesOld);

		foreach ($e as $attributeName => $attributeValue) {
			if (!isset($this->attributesOld[$attributeName]) || null === ArrayHelper::getValue($this->attributesOld, $attributeName)) {
				$diff[] = new HistoryEventAction([
					'attributeName' => ArrayHelper::getValue($labels, $attributeName, $attributeName),
					'attributeNewValue' => $this->SubstituteAttributeValue($attributeName, $attributeValue),
					'type' => HistoryEventAction::ATTRIBUTE_CREATED
				]);
			}
		}

		return $diff;
	}

	/**
	 * @param string $attributeName Название атрибута, для которого пытаемся найти подстановку
	 * @param mixed $attributeValue Значение атрибута, которому ищем соответствие
	 * @return mixed Подстановленное значение (если найдено, иначе переданное значение)
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	private function SubstituteAttributeValue(string $attributeName, mixed $attributeValue) {
		if (null === $this->loadedModel) return $attributeValue;
		if (null === $attributeConfig = $this->getModelRules("attributes.{$attributeName}")) return $attributeValue;
		if (false === $attributeConfig) return false;//не показывать атрибут
		if (ReflectionHelper::is_closure($attributeConfig)) {
			return $attributeConfig($attributeName, $attributeValue);
		}
		if (is_array($attributeConfig)) {//[className => valueAttribute]
			/** @var string $fromModelName */
			$fromModelName = ArrayHelper::key($attributeConfig);
			/** @var ActiveRecord $fromModel */
			$fromModel = ReflectionHelper::LoadClassByName($fromModelName);
			$modelValueAttribute = $attributeConfig[$fromModelName];
			return ArrayHelper::getValue($fromModel::findOne($attributeValue), $modelValueAttribute, $attributeValue);
		} else return $attributeConfig;//Можем вернуть прямо заданное значение
	}

	/**
	 * Возвращает массив атрибутов, различающихся в заданных уровнях
	 * @param $level1
	 * @param $level2
	 * @return array
	 * @throws InvalidConfigException
	 * @throws Throwable
	 * @test me
	 */
	public function getAttributesDiff($level1, $level2):array {
		return self::ArrayKeyValueDiffAssocRecursive($this->getModelHistory($level1), $this->getModelHistory($level2));
	}

	/**
	 * Вернёт массив ключей, значения у которых различаются в обеих массивах
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 */
	public static function ArrayKeyValueDiffAssocRecursive(array $array1, array $array2):array {
		$intersection = array_uintersect_assoc($array1, $array2, static function($value1, $value2) {
			if (is_scalar($value1) && is_scalar($value2)) {
				return (string)$value1 === (string)$value2;//Внимание! при возврате bool, учитываем, что (bool)true == (int)1
			}
			if (is_array($value1) && is_array($value2)) {
				return $value1 === $value2;
			}
			if ((is_array($value1) && is_scalar($value2)) || (is_array($value2) && is_scalar($value1))) {
				return false;
			}
			return true;//null;
		});//получили разницу в существующих везде атрибутах

		$separate_keys = array_diff_key($array1, $array2);

		return array_merge(array_keys($intersection), array_keys($separate_keys));
	}

	/**
	 * @param int $level
	 * @return self|null
	 * @throws InvalidConfigException
	 * @noinspection PhpIncompatibleReturnTypeInspection - мы конкретизируем возвращаемое значение
	 */
	private function getHistoryLevelRecord(int $level):?self {
		if ($level < 1) return null;
		return self::find()
			->where(['operation_identifier' => self::find()
				->select(['operation_identifier'])
				->where(['model_class' => $this->getStoredClassName(), 'model_key' => $this->loadedModel->primaryKey])
				->groupBy(['operation_identifier'])
				->orderBy(['MAX(id)' => SORT_DESC])
				->offset($level - 1)
				->limit(1)
				->all()])
			->one();
	}

	/**
	 * Дата изменения уровня
	 * @param int $level
	 * @return string|null
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function getHistoryVersionInfo(int $level):?string {
		return ArrayHelper::getValue($this->getHistoryLevelRecord($level), 'at');
	}

	/**
	 * Тег изменения
	 * @param int $level
	 * @return string|null
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function getHistoryTag(int $level):?string {
		return ArrayHelper::getValue($this->getHistoryLevelRecord($level), 'tag');
	}

	/**
	 * Автор изменения
	 * @param int $level
	 * @param bool $delegate -- проверять делегацию
	 * @return int|null
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function getHistoryCreator(int $level, bool $delegate = false):?int {
		$record = $this->getHistoryLevelRecord($level);
		if ($delegate && null !== $result = ArrayHelper::getValue($record, 'delegate')) return $result;
		return ArrayHelper::getValue($record, 'user');
	}

	/**
	 * Количество имеющихся версий истории для модели
	 * @return int
	 * @throws InvalidConfigException
	 */
	public function getHistoryLevelCount():int {
		return (int)self::find()->select('operation_identifier')->where(['model_class' => $this->getStoredClassName(), 'model_key' => $this->loadedModel->primaryKey])->distinct()->count('operation_identifier');
	}

	/**
	 * Возвращает все записи, относящиеся к указанному изменению
	 * @param string $step_identifier
	 * @return self[]
	 * @throws InvalidConfigException
	 */
	private function getStepHistory(string $step_identifier):array {
		return self::find()->where(['operation_identifier' => $step_identifier, 'model_class' => $this->getStoredClassName(), 'model_key' => $this->loadedModel->primaryKey])->orderBy(['id' => SORT_DESC])->all();
	}

	/**
	 * Возвращает идентификаторы всех шагов истории для данной модели
	 * @return string[]
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	private function getModelHistoryStepsIdentifiers():array {
		return ArrayHelper::keymap(self::find()
			->select(['operation_identifier'])
			->where(['model_class' => $this->getStoredClassName(), 'model_key' => $this->loadedModel->primaryKey])
			->groupBy(['operation_identifier'])
			->orderBy(['MAX(id)' => SORT_DESC])
			->asArray()
			->all(), 'operation_identifier');
	}

	/**
	 * По переданной модели возвращает массив атрибутов её версию на $historyLevel шагов вниз
	 * Если запрошенной версии не существует -- вернёт последнюю найденную
	 * @param int $historyLevel -- уровень истории, 0 - текущая версия, 1 - предыдущая и т.д.
	 * @return array
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function getModelHistory(int $historyLevel = 0):array {
		if ($this->loadedModel->isNewRecord) throw new InvalidConfigException('Provided model must have a primary key');
		$resultModelData = $this->loadedModel->attributes;
		$relationAttributes = $this->getModelRules('relations', []);
		foreach ($relationAttributes as $relationAttribute => $relationRule) {
			if ((is_array($relationRule))) {
				$resultModelData[$relationAttribute] = ArrayHelper::getColumn($this->loadedModel->$relationAttribute, array_shift($relationRule));
			} elseif (null === $relationRule) {
				$resultModelData[$relationAttribute] = $this->loadedModel->$relationAttribute;
			} else {
				$resultModelData[$relationAttribute] = ArrayHelper::getValue($this->loadedModel->$relationAttribute, $relationRule);
			}
		}
		unset($resultModelData[(string)ArrayHelper::getValue($this->loadedModel::primaryKey(), 0, 'id')]);//сбрасываем ключ, чтобы не срабатывали геттеры при построении моделей

		$modelHistoryStepsIdentifiers = $this->getModelHistoryStepsIdentifiers();
		for ($currentHistoryLevel = 0; $currentHistoryLevel < $historyLevel; $currentHistoryLevel++) {
			if ($currentHistoryLevel > count($modelHistoryStepsIdentifiers) - 1) {//выходим за пределы истории
				break;
			}
			$currentStepRecords = $this->getStepHistory($modelHistoryStepsIdentifiers[$currentHistoryLevel]);
			foreach ($currentStepRecords as $historyData) {

				foreach ($historyData->attributesNew as $attributeName => $attributeValue) {//откатываем добавленные атрибуты
					if ((null === $currentVal = (ArrayHelper::getValue($resultModelData, $attributeName))) || is_scalar($currentVal)) {
						$resultModelData[$attributeName] = null;
					} elseif (is_array($currentVal)) {
						$resultModelData[$attributeName] = array_diff((array)ArrayHelper::getValue($resultModelData, $attributeName, []), is_array($attributeValue)?$attributeValue:(array)$attributeValue);//обойдёмся без рекурсивности
					} else {
						throw new UnknownPropertyException("Не могу разобрать конфигурацию атрибута истории: {$attributeName} для {$this->loadedModel->formName()}");
					}
				}

				foreach ($historyData->attributesOld as $attributeName => $attributeValue) {//накатываем удалённые атрибуты
					if (is_array($resultModelData[$attributeName])) {
						$resultModelData[$attributeName] = array_merge_recursive($resultModelData[$attributeName], (is_array($attributeValue))?$attributeValue:(array)$attributeValue);
					} else {
						$resultModelData[$attributeName] = $attributeValue;
					}
				}
			}

		}

		return $resultModelData;
	}

	/**
	 * @param int $modelKey
	 * @return ActiveQuery
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function getHistory(int $modelKey):ActiveQuery {
		return self::find()
			->where(['model_class' => null === $this->loadedModel?$this->model_class:$this->getStoredClassName(), 'model_key' => $modelKey])->orderBy('at');
	}

	/**
	 * @param string $shortClassName
	 * @return string
	 * @throws Throwable
	 */
	public static function ExpandClassName(string $shortClassName):string {
		return ArrayHelper::getValue(ModuleHelper::params(HistoryModule::class), "classNamesMap.$shortClassName", $shortClassName);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedHistoryTags():ActiveQuery {
		return $this->hasOne(HistoryTags::class, ['history' => 'id']);
	}

	/**
	 * @return string|null
	 * @throws Throwable
	 */
	public function getTag():?string {
		$ids = ArrayHelper::getColumn(self::find()->where(['model_class' => $this->model_class, 'model_key' => $this->model_key, 'operation_identifier' => $this->operation_identifier])->select('id')->asArray()->all(), 'id');
		return ArrayHelper::getValue(HistoryTags::find()->where(['in', 'history', $ids])->one(), 'tag');
	}

	/**
	 * @param string $tag
	 * @throws InvalidConfigException
	 * @throws Exception
	 */
	public function setTag(string $tag):void {
		HistoryTags::addInstance(['history' => $this->id, 'tag' => $tag], null, true, true);
	}

	/**
	 * Кривенький-плохонький но рабочий метод получения глубины изменения по его тегу
	 * @param string $tag
	 * @return int|null
	 * @throws InvalidConfigException
	 */
	public function getTagHistoryLevel(string $tag):?int {
		/** @var self[] $modelHistory */
		$modelHistory = self::find()->where(['model_class' => $this->getStoredClassName($this->loadedModel), 'model_key' => $this->loadedModel->primaryKey])->joinWith(['relHistoryTags'])->orderBy(['id' => SORT_DESC])->all();
		$result = 0;
		$oi = '';
		foreach ($modelHistory as $value) {
			if ($value->tag === $tag) return $result;
			if ($oi !== $value->operation_identifier) {
				$oi = $value->operation_identifier;
				$result++;
			}

		}
		return null;
	}

	/**
	 * @param $value
	 * @return string
	 */
	protected function serialize($value):string {
		return (null === $this->serializer)?serialize($value):call_user_func($this->serializer[0], $value);
	}

	/**
	 * @param string|mixed $value
	 * @return mixed
	 */
	protected function unserialize(mixed $value) {
		if (is_resource($value) && 'stream' === get_resource_type($value)) {
			$serialized = stream_get_contents($value);
			fseek($value, 0);
		} else {
			$serialized = $value;
		}
		return (null === $this->serializer)?unserialize($serialized, ['allowed_classes' => true]):call_user_func($this->serializer[1], $serialized);
	}

	/**
	 * @return mixed
	 */
	public function getAttributesOld() {
		if (null === $this->_oldAttributes) $this->_oldAttributes = $this->unserialize($this->old_attributes);
		return $this->_oldAttributes;
	}

	/**
	 * @param mixed $attributesOld
	 */
	public function setAttributesOld(mixed $attributesOld):void {
		$this->old_attributes = $this->serialize($attributesOld);
		$this->_oldAttributes = null;
	}

	/**
	 * @return mixed
	 */
	public function getAttributesNew() {
		if (null === $this->_newAttributes) $this->_newAttributes = $this->unserialize($this->new_attributes);
		return $this->_newAttributes;
	}

	/**
	 * @param mixed $attributesNew
	 */
	public function setAttributesNew(mixed $attributesNew):void {
		$this->new_attributes = $this->serialize($attributesNew);
		$this->_newAttributes = null;
	}
}