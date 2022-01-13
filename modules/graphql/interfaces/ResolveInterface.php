<?php
declare(strict_types = 1);

namespace app\modules\graphql\interfaces;

use app\modules\graphql\components\ResolveParameter;

/**
 * Interface ResolveInterface
 */
interface ResolveInterface {

	/**
	 * @param ResolveParameter $resolveParameter
	 * @return array
	 */
	public static function resolve(ResolveParameter $resolveParameter):array;

}