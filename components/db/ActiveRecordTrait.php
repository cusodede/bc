<?php
declare(strict_types = 1);

namespace app\components\db;

use app\models\sys\permissions\traits\ActiveRecordPermissionsTrait;
use pozitronik\filestorage\traits\FileStorageTrait;
use pozitronik\helpers\ArrayHelper;
use pozitronik\traits\traits\ActiveRecordTrait as VendorActiveRecordTrait;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\db\Exception as DbException;
use yii\bootstrap4\ActiveForm;
use yii\db\Transaction;
use yii\web\UploadedFile;

/**
 * Trait ActiveRecordTrait
 * Попытка переделать правильно трейт с вспомогательными функциями ActiveRecord-классов
 */
trait ActiveRecordTrait {
	use VendorActiveRecordTrait;
	use ActiveRecordPermissionsTrait;

	/**
	 * @return ActiveQuery
	 */
	public static function find():ActiveQuery {
		return new ActiveQuery(static::class);
	}

	/**
	 * По (int)$pk|(string)$pk пытается вернуть соответствующую ActiveRecord-модель
	 * @param null|string|ActiveRecordInterface $className
	 * @param int|string|ActiveRecordInterface $model
	 * @return ActiveRecordInterface|null
	 */
	public static function ensureModel(null|string|ActiveRecordInterface $className, int|string|ActiveRecordInterface $model):?ActiveRecordInterface {
		if (is_string($model) && is_numeric($model)) {
			$model = (int)$model;
		}
		if (is_int($model)) {
			/** @var ActiveRecordInterface $className */
			$model = $className::findOne($model);
		}
		return is_a($model, ActiveRecordInterface::class, false)?$model:null;
	}

	/**
	 * @inheritDoc
	 * @param ActiveRecordInterface|int|string $model the model to be linked with the current one.
	 */
	public function link($name, $model, $extraColumns = []):void {
		/** @noinspection PhpMultipleClassDeclarationsInspection
		 * parent всегда будет ссылаться на BaseActiveRecord, но у нас нет способа это пометить
		 */
		parent::link($name, self::ensureModel($this->$name, $model), $extraColumns);
	}

	/**
	 * @param array $errors Возвращаемый список ошибок. null, чтобы не инициализировать на входе.
	 * @param null|bool $AJAXErrorsFormat Формат возврата ошибок: true: для ajax-валидации, false - as is, null (default) - в зависимости от типа запроса
	 * @param array $relationAttributes Массив с перечисление relational-моделей, приходящих отдельной формой
	 * @return null|bool true: модель сохранена, false: модель не сохранена, null: постинга не было
	 * @throws DbException
	 * @param-out array $errors На выходе всегда будет массив
	 */
	public function createModelFromPost(array &$errors = [], ?bool $AJAXErrorsFormat = null, array $relationAttributes = []):?bool {
		$errors = [];
		if ($this->load(Yii::$app->request->post())) {
			/**
			 * Все изменения заключаются в транзакцию с тем, чтобы откатывать сохранения записей, задаваемых в relational-атрибутах
			 * @var Transaction $transaction
			 */
			$transaction = static::getDb()->beginTransaction();
			/**
			 * Если сохранение одной модели завязано на сохранение другой модели, привязанной через relational-атрибут,
			 * то пытаемся сохранить связанную модель, при неудаче - откатываемся.
			 */
			if ([] !== $relationAttributes) {
				foreach ($relationAttributes as $relationAttributeName) {
					if ($this->hasProperty($relationAttributeName) && $this->canSetProperty($relationAttributeName)
						&& false === $this->$relationAttributeName->createModelFromPost($errors, $AJAXErrorsFormat)) {
						/*Ошибка сохранения связанной модели, откатим изменения*/
						$transaction->rollBack();
						return false;
					}
				}
			}

			if (false !== $result = $this->save()) {
				$transaction->commit();
			} else {
				if (null === $AJAXErrorsFormat) $AJAXErrorsFormat = Yii::$app->request->isAjax;
				/** @var ActiveRecord $this */
				$errors = $AJAXErrorsFormat
					?ActiveForm::validate($this)
					:$this->errors;
				$transaction->rollBack();
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
	 * @param array $errors Возвращаемый список ошибок. null, чтобы не инициализировать на входе.
	 * @param null|bool $AJAXErrorsFormat Формат возврата ошибок: true: для ajax-валидации, false - as is, null (default) - в зависимости от типа запроса
	 * @param array $relationAttributes Массив с перечисление relational-моделей, приходящих отдельной формой
	 * @return null|bool true: модель сохранена, false: модель не сохранена, null: постинга не было
	 * @throws DbException
	 * @param-out array $errors На выходе всегда будет массив
	 */
	public function updateModelFromPost(array &$errors = [], ?bool $AJAXErrorsFormat = null, array $relationAttributes = []):?bool {
		/* Методы совпадают, но оставлено на будущее */
		return $this->createModelFromPost($errors, $AJAXErrorsFormat, $relationAttributes);
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
	 * @return bool
	 */
	public function setAndSaveAttribute(string $name, $value):bool {
		//для избежания дублирования логики функционала
		return $this->setAndSaveAttributes([$name => $value]);
	}

	/**
	 * Работает аналогично saveAttributes, но сразу сохраняет данные
	 * Отличается от updateAttributes тем, что триггерит onAfterSave
	 * @param null|array $values
	 * @param bool $safeOnly
	 * @return bool
	 */
	public function setAndSaveAttributes(?array $values, bool $safeOnly = false):bool {
		if ($safeOnly) {
			//Уберем unsafe атрибуты, чтобы избежать возможных ошибок при доступе к этим свойствам.
			$values = array_intersect_key($values, array_flip($this->safeAttributes()));

			$this->setAttributes($values);
		} else {
			//[[ActiveRecord::attributes()]] возвращает атрибуты, которые генерирует по схеме из БД,
			//и, как следствие, не учитывает кастомные свойства в модели.
			//Поэтому используем нативный сеттинг.
			foreach ($values as $name => $value) {
				if ($this->canSetProperty($name)) {
					$this->$name = $value;
				} else {
					unset($values[$name]);
				}
			}
		}

		/** @var Transaction $transaction */
		$transaction = Yii::$app->db->beginTransaction();
		try {
			//При обновлении записи не будем лишний раз дергать проверку атрибутов, не заданных в `$values`.
			$saveIsOk = $this->save(true, $this->isNewRecord ? null : array_keys($values));
			if ($saveIsOk && method_exists($this, 'uploadAttribute')) {
				//Ищем файловые атрибуты для их загрузки в хранилище.
				foreach ($values as $name => $value) {
					//Получаем атрибуты непосредственно из модели, т.к. в процессе сохранения часть из них могла модифицироваться,
					//например, при сеттинге атрибута через raw data.
					if ($this->$name instanceof UploadedFile) {
						/** @see FileStorageTrait::uploadAttribute() */
						$this->uploadAttribute($name);
					}
				}
			}

			if ($saveIsOk) {
				$transaction->commit();
			} else {
				$transaction->rollBack();
			}
		} /** @noinspection BadExceptionsProcessingInspection */ catch (Throwable) {
			$transaction->rollBack();

			$saveIsOk = false;
		}

		return $saveIsOk;
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

	/**
	 * Если модель с текущими атрибутами есть - вернуть её. Если нет - создать и вернуть.
	 * @noinspection PhpDocMissingThrowsInspection Это нормально, метод find может быть перекрыт, и возвращать
	 * собственные исключения. Можно не включать в пробрасываемый скоуп.
	 */
	public static function Upsert(array $attributes):static {
		if (null === $model = self::find()->where($attributes)->one()) {
			$model = new self();
			$model->load($attributes, '');
			$model->save();
		}
		return $model;
	}

	/**
	 * Возвращает существующую запись в ActiveRecord-модели, найденную по условию, если же такой записи нет - возвращает новую модель
	 */
	public static function getInstance(array|string $searchCondition):static {
		$instance = static::find()->where($searchCondition)->one();
		return $instance??new static();
	}

}