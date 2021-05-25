<?php
declare(strict_types = 1);

namespace app\models\core\prototypes;

use pozitronik\core\models\LCQuery;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception as DbException;
use yii\db\Transaction;
use yii\bootstrap4\ActiveForm;

/**
 * Trait ActiveRecordTrait
 * Попытка переделать правильно трейт с вспомогательными функциями ActiveRecord-классов
 */
trait ActiveRecordTrait {

	/**
	 * @return LCQuery
	 */
	public static function find():LCQuery {
		return new LCQuery(static::class);
	}

	/**
	 * @param array $mappedParams
	 * @param null|array $errors Возвращаемый список ошибок. null, чтобы не инициализировать на входе.
	 * @param null|bool $AJAXErrorsFormat Формат возврата ошибок: true: для ajax-валидации, false - as is, null (default) - в зависимости от типа запроса
	 * @return null|bool null
	 * @throws DbException
	 * @throws Throwable
	 * @param-out array $errors На выходе всегда будет массив
	 */
	public function createModelFromPost(array $mappedParams = [], ?array &$errors = [], ?bool $AJAXErrorsFormat = null):?bool {
		$errors = [];
		if ($this->load(Yii::$app->request->post())) {
			$result = $this->createModel(Yii::$app->request->post($this->formName(), []), $mappedParams);
			if (!$result) {
				if (null === $AJAXErrorsFormat) $AJAXErrorsFormat = Yii::$app->request->isAjax;
				/** @var ActiveRecord $this */
				$errors = $AJAXErrorsFormat
					?ActiveForm::validate($this)
					:$this->errors;
			}

			return $result;
		}
		return null;
	}

	/**
	 * Валидация для ajax-validation
	 * @return null|array
	 * @throws Throwable
	 */
	public function validateModelFromPost():?array {
		if ($this->load(Yii::$app->request->post())) {
			/** @var ActiveRecord $this */
			return ActiveForm::validate($this);
		}
		return null;
	}

	/**
	 * @param array $mappedParams
	 * @param null|array $errors Возвращаемый список ошибок. null, чтобы не инициализировать на входе.
	 * @param null|bool $AJAXErrorsFormat Формат возврата ошибок: true: для ajax-валидации, false - as is, null (default) - в зависимости от типа запроса
	 * @return null|bool
	 * @throws DbException
	 * @throws Throwable
	 * @param-out array $errors На выходе всегда будет массив
	 */
	public function updateModelFromPost(array $mappedParams = [], ?array &$errors = [], ?bool $AJAXErrorsFormat = null):?bool {
		/* Методы совпадают, но оставлено на будущее */
		return $this->createModelFromPost($mappedParams, $errors, $AJAXErrorsFormat);
	}

	/**
	 * Метод создания модели, выполняющий дополнительную обработку:
	 *    Обеспечивает последовательное создание модели и заполнение данных по связям (т.е. тех данных, которые не могут быть заполнены до фактического создания модели).
	 *    Последовательность заключена в транзакцию - сбой на любом шаге ведёт к отмене всей операции.
	 *
	 * @param array $paramsArray Массив параметров БЕЗ учёта имени модели в форме (я забыл, почему сделал так, но, видимо, причина была)
	 * @param array $mappedParams Массив с параметрами для реляционных атрибутов в формате 'имя атрибута' => массив значений
	 * @return bool - результат операции
	 * @throws Throwable
	 * @throws DbException
	 */
	public function createModel(array $paramsArray = [], array $mappedParams = []):bool {
		/** @var Transaction $transaction */
		$transaction = static::getDb()->beginTransaction();
		if (true === $saved = $this->save()) {
			$this->refresh();//переподгрузим атрибуты
			/*Возьмём разницу атрибутов и массива параметров - в нем будут новые атрибуты, которые теперь можно заполнить*/
			$relatedParameters = [];
			foreach ($paramsArray as $item => $value) {//вычисляем связанные параметры, которые не могли быть сохранены до сохранения основной модели
				if ($this->canSetProperty($item) && $value !== $this->$item) {
					$relatedParameters[$item] = $value;
				}
			}
			$mappedParams = array_merge($mappedParams, $relatedParameters);

			if ([] !== $mappedParams) {//если было, что сохранять - сохраним
				foreach ($mappedParams as $paramName => $paramArray) {//дополнительные атрибуты в формате 'имя атрибута' => $paramsArray
					$this->$paramName = $paramArray;
				}
				$saved = $this->save();
				$this->refresh();
			}
		}
		if ($saved) {
			$transaction->commit();
		} else {
			$transaction->rollBack();
		}
		return $saved;
	}

	/**
	 * Метод обновления модели, выполняющий дополнительную обработку
	 * @param array $paramsArray Массив параметров БЕЗ учёта имени модели в форме (я забыл, почему сделал так, но, видимо, причина была)
	 * @param array $mappedParams Массив с параметрами для реляционных атрибутов в формате 'имя атрибута' => массив значений
	 * @return bool
	 * @throws Throwable
	 */
	public function updateModel(array $paramsArray = [], array $mappedParams = []):bool {
		return $this->createModel($paramsArray, $mappedParams);
	}

	/**
	 * Универсальная функция удаления любой модели
	 */
	public function safeDelete():void {
		if ($this->hasAttribute('deleted')) {
			$this->setAndSaveAttribute('deleted', !$this->deleted);
			$this->afterDelete();
		} else {
			$this->delete();
		}
	}

	/**
	 * Работает аналогично saveAttribute, но сразу сохраняет данные
	 * Отличается от updateAttribute тем, что триггерит onAfterSave
	 * @param string $name
	 * @param mixed $value
	 */
	public function setAndSaveAttribute(string $name, $value):void {
		$this->setAttribute($name, $value);
		$this->save();
	}

	/**
	 * Работает аналогично saveAttributes, но сразу сохраняет данные
	 * Отличается от updateAttributes тем, что триггерит onAfterSave
	 * @param null|array $values
	 */
	public function setAndSaveAttributes(?array $values):void {
		$this->setAttributes($values, false);
		$this->save();
	}

	/**
	 * Разница изменений атрибутов после обновления модели
	 * @param bool $strict Строгое сравнение
	 * @return array
	 * @throws Throwable
	 */
	public function identifyUpdatedAttributes(bool $strict = true):array {
		$changedAttributes = [];
		foreach ($this->attributes as $name => $value) {
			/** @noinspection TypeUnsafeComparisonInspection */
			$changed = $strict?(ArrayHelper::getValue($this, "oldAttributes.$name") !== $value):(ArrayHelper::getValue($this, "oldAttributes.$name") != $value);
			if ($changed) $changedAttributes[$name] = $value;//Нельзя использовать строгое сравнение из-за преобразований БД
		}
		return $changedAttributes;
	}

	/**
	 * Изменилось ли значение атрибута после обновления модели
	 * @param string $attribute
	 * @param bool $strict Строгое сравнение
	 * @return bool
	 * @throws Throwable
	 */
	public function isAttributeUpdated(string $attribute, bool $strict = true):bool {
		/** @noinspection TypeUnsafeComparisonInspection */
		return $strict?(ArrayHelper::getValue($this, "oldAttributes.$attribute") !== $this->$attribute):(ArrayHelper::getValue($this, "oldAttributes.$attribute") != $this->$attribute);
	}

}