<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use Throwable;

/**
 * Class BaseInputObjectType
 * Базовый класс для всех инпутов
 */
class BaseInputObjectType extends InputObjectType {

	/**
	 * @throws Throwable
	 */
	public static function type():Type {
		return TypeLoader::type(static::class);
	}

}