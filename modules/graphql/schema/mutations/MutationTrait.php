<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use Exception;

/**
 * Трейт для GraphQL мутаций
 * Trait MutationTrait
 * @package app\modules\graphql\schema\mutations
 */
trait MutationTrait
{
	/**
	 * Обновление сущностей
	 * @param array $rootAttributes
	 * @param array $attributes
	 * @return array
	 * @throws Exception
	 */
	public function update(array $rootAttributes, array $attributes): array
	{
		$model = $this->model::findOne($rootAttributes);
		return $this->save($model, $attributes, self::MESSAGES);
	}

	/**
	 * Создание сущности
	 * @param array $attributes
	 * @return array
	 * @throws Exception
	 */
	public function create(array $attributes): array
	{
		$model = new $this->model();
		return $this->save($model, $attributes, self::MESSAGES);
	}

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