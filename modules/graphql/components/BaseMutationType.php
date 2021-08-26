<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use app\components\db\ActiveRecordTrait;
use app\modules\graphql\interfaces\ResolveInterface;
use app\modules\graphql\traits\BaseObjectTrait;
use Exception;
use GraphQL\Type\Definition\FieldDefinition;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class BaseMutationType
 */
abstract class BaseMutationType extends FieldDefinition implements ResolveInterface
{
	use BaseObjectTrait;

	/**
	 * Сохранение модели для GraphQL
	 * @param ActiveRecord $model
	 * @param array $attributes
	 * @param array $messages
	 * @return array
	 * @throws Exception
	 */
	public static function save(ActiveRecord $model, array $attributes, array $messages): array
	{
		/**
		 * Если в числовом атрибуте приходит -1, значит ребята с фронта, просят исключить
		 * этот атрибут из массива на обновление. Не учитывает строковые значения.
		 */
		$attributes = array_filter($attributes, static fn($value) => (-1 !== $value));
		/** @var ActiveRecord|ActiveRecordTrait $model */
		return static::getResult($model->setAndSaveAttributes($attributes, true), $model->getErrors(), $messages);
	}

	/**
	 * Формируем результат ответа
	 * @param bool $result
	 * @param array $modelErrors
	 * @param array $messages
	 * @return array
	 * @throws Exception
	 */
	public static function getResult(bool $result, array $modelErrors, array $messages):array {
		return [
			'result' => $result,
			'message' => static::getMessage($result, $messages),
			'errors' => static::getErrors($modelErrors),
		];
	}

	/**
	 * Преобразует ассоциативный массив $model->getErrors()
	 * в массив для GraphQL
	 * @param array $modelErrors
	 * @return array
	 */
	public static function getErrors(array $modelErrors):array {
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
	public static function getMessage(bool $result, array $messages): string
	{
		return ArrayHelper::getValue($messages, $result, '');
	}
}