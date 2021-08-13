<?php
declare(strict_types = 1);

namespace app\models\sys\permissions\traits;

use app\components\db\ActiveQuery;
use app\models\sys\users\Users;
use Throwable;
use yii\web\ForbiddenHttpException;

/**
 * Trait ActiveQueryPermissionsTrait
 * Управление областями видимости в ActiveQuery
 */
trait ActiveQueryPermissionsTrait {
	/**
	 * Возвращает область видимости пользователя $user для модели $modelClass (если та реализует метод self::scope);
	 * @param string|null $modelObjectOrClass
	 * @param ?Users $user
	 * @return ActiveQueryPermissionsTrait|ActiveQuery
	 * @throws ForbiddenHttpException
	 * @throws Throwable
	 */
	public function scope(?string $modelObjectOrClass = null, ?Users $user = null):self {
		$modelObjectOrClass = $modelObjectOrClass??$this->modelClass;
		if (method_exists($modelObjectOrClass, 'scope')) {
			$user = $user??Users::Current();
			/** @var ActiveRecordPermissionsTrait $modelObjectOrClass */
			return ($modelObjectOrClass::scope($this, $user));
		}

		return $this;
	}

}