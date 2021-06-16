<?php
declare(strict_types = 1);

namespace app\modules\graphql;

/**
 * Class GraphqlHelper
 * @package app\modules\graphql
 */
class GraphqlHelper
{
	/**
	 * Преобразует ассоциативный массив $model->getErrors()
	 * в массив для GraphQL
	 * @param array $errors
	 * @return array[]
	 */
	public static function getErrors(array $errors): array
	{
		$modelErrors = [];
		foreach ($errors as $field => $messages) {
			$modelErrors[] = compact('field', 'messages');
		}
		return ['errors' => $modelErrors];
	}
}