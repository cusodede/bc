<?php
declare(strict_types = 1);

namespace app\models\core\prototypes;

use pozitronik\core\models\LCQuery;
use Throwable;
use Yii;
use yii\db\Exception as DbException;
use yii\db\Transaction;

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
	 * @return bool
	 * @throws DbException
	 * @throws Throwable
	 */
	public function createModelFromPost(array $mappedParams = []):bool {
		if ($this->load(Yii::$app->request->post())) {
			return $this->createModel(Yii::$app->request->post($this->formName(), []), $mappedParams);
		}
		return false;
	}

	/**
	 * @param array $mappedParams
	 * @return bool
	 * @throws DbException
	 * @throws Throwable
	 */
	public function updateModelFromPost(array $mappedParams = []):bool {
		if ($this->load(Yii::$app->request->post())) {
			return $this->createModel(Yii::$app->request->post($this->formName(), []), $mappedParams);
		}
		return false;
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

}