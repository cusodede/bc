<?php
declare(strict_types = 1);

namespace app\modules\graphql;

use yii\base\Model;

/**
 * Трейт для GraphQL мутаций
 * Trait MutationTrait
 * @package app\modules\graphql
 */
trait MutationTrait
{
	/**
	 * @param Model $model
	 * @param array $attributes
	 * @return array
	 */
	public function save(Model $model, array $attributes): array
	{
		$model->setAttributes($attributes);
		return $model->save() ? $this->getResult(true, 'Партнер успешно обновлён') :
			$this->getErrors($model->getErrors());
	}
	/**
	 * Преобразует ассоциативный массив $model->getErrors()
	 * в массив для GraphQL
	 * @param array $errors
	 * @return array[]
	 */
	public function getErrors(array $errors): array
	{
		$modelErrors = [];
		foreach ($errors as $field => $messages) {
			$modelErrors[] = compact('field', 'messages');
		}
		return ['errors' => $modelErrors];
	}

	/**
	 * Ответ на создание/обновление
	 * @param bool $result
	 * @param string $message
	 * @return array
	 */
	public function getResult(bool $result,  string $message = ''): array
	{
		return compact('result', 'message');
	}
}