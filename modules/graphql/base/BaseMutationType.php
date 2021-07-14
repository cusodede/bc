<?php
declare(strict_types = 1);

namespace app\modules\graphql\base;

use GraphQL\Type\Definition\ObjectType;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use Exception;
use yii\base\InvalidArgumentException;

/**
 * Class BaseMutationType
 * @package app\modules\graphql\base
 */
abstract class BaseMutationType extends ObjectType
{
	/**
	 * @var ActiveRecord|null
	 */
	protected ?ActiveRecord $model;

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
	 * @param ActiveRecord|null $model
	 * @param array $attributes
	 * @param array $messages
	 * @return array
	 * @throws Exception
	 */
	public function save(ActiveRecord $model, array $attributes, array $messages): array
	{
		if (null === $model) {
			throw new InvalidArgumentException('Невозможно обнаружить соответствующую модель.');
		}
		$model->setAttributes($attributes);
		return $this->getResult($model->save(), $model->getErrors(), $messages);
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
