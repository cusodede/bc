<?php
declare(strict_types = 1);

namespace app\components\db;

use app\models\sys\users\Users;
use pozitronik\traits\models\ActiveQuery as VendorActiveQuery;

/**
 * Trait ActiveQueryTrait
 * Каст расширения запросов
 */
class ActiveQuery extends VendorActiveQuery {

	/**
	 * Возвращает область видимости пользователя $user для модели $modelClass (если та реализует метод self::scope);
	 * @param string|object $modelObjectOrClass
	 * @param Users $user
	 * @return $this
	 */
	public function scope(string $modelObjectOrClass, Users $user):self {
		if (method_exists($modelObjectOrClass, 'scope')) {
			return ($modelObjectOrClass::scope($this, $user));
		}

		return $this;
	}
}