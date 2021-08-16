<?php
declare(strict_types = 1);

namespace app\modules\graphql\base;

use app\components\db\ActiveRecordTrait;
use GraphQL\Type\Definition\ObjectType;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use Exception;

/**
 * Class BaseMutationType
 * @package app\modules\graphql\base
 */
abstract class BaseMutationType extends ObjectType
{
	/**
	 * Список сообщение для фронта
	 */
	public const MESSAGES = [];

	/**
	 * Схема для мутаций
	 * @return array
	 */
	abstract public static function mutationType(): array;

	/**
	 * Список атрибутов GraphQL типа
	 * @return array
	 */
	abstract public function getArgs(): array;

	/**
	 * Доступные методы
	 * @return array
	 */
	abstract public function getConfig(): array;

	/**
	 * Сохранение модели для GraphQL
	 * @param ActiveRecord $model
	 * @param array $attributes
	 * @param array $messages
	 * @return array
	 * @throws Exception
	 */
	public function save(ActiveRecord $model, array $attributes, array $messages): array
	{
		/**
		 * Если в числовом атрибуте приходит -1, значит ребята с фронта, просят исключить
		 * этот атрибут из массива на обновление. Не учитывает строковые значения.
		 */
		$attributes = array_filter($attributes, static fn($value) => (-1 !== $value));
		/** @var ActiveRecord|ActiveRecordTrait $model */
		return $this->getResult($model->setAndSaveAttributes($attributes, true), $model->getErrors(), $messages);
	}

	/**
	 * Формируем результат ответа
	 * @param bool $result
	 * @param array $modelErrors
	 * @param array $messages
	 * @return array
	 * @throws Exception
	 */
	public function getResult(bool $result, array $modelErrors, array $messages): array
	{
		return [
			'result' => $result,
			'message' => $this->getMessage($result, $messages),
			'errors' => $this->getErrors($modelErrors),
		];
	}

	/**
	 * Преобразует ассоциативный массив $model->getErrors()
	 * в массив для GraphQL
	 * @param array $modelErrors
	 * @return array
	 */
	public function getErrors(array $modelErrors): array
	{
		$errors = [];
		foreach ($modelErrors as $field => $messages) {
			$errors[] = compact('field', 'messages');
		}
		return $errors;
	}

	/**
	 * Сообщение для ответа (popup)
	 * @param bool $result
	 * @param array $messages
	 * @return string
	 * @throws Exception
	 */
	public function getMessage(bool $result, array $messages): string
	{
		return ArrayHelper::getValue($messages, $result, '');
	}
}
