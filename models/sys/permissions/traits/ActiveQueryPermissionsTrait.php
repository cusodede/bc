<?php
declare(strict_types = 1);

namespace app\models\sys\permissions\traits;

use app\components\db\ActiveQuery;
use app\components\Options;
use app\models\sys\users\Users;
use Throwable;
use yii\db\ActiveQueryInterface;
use yii\web\ForbiddenHttpException;

/**
 * Trait ActiveQueryPermissionsTrait
 * Управление областями видимости в ActiveQuery
 */
trait ActiveQueryPermissionsTrait {
	/**
	 * Возвращает область видимости пользователя $user для модели $modelClass (если та реализует метод self::scope);
	 * @param object|string|null $modelObjectOrClass
	 * @param ?Users $user
	 * @return self|ActiveQuery
	 * @throws ForbiddenHttpException
	 * @throws Throwable
	 */
	public function scope(object|string $modelObjectOrClass = null, ?Users $user = null):self {
		if (Options::getValue(Options::SCOPE_IGNORE_ENABLE)) return $this;

		/** @var ActiveQueryInterface $this */
		$modelObjectOrClass = $modelObjectOrClass??$this->modelClass;
		if (method_exists($modelObjectOrClass, 'scope')) {
			$user = $user??Users::Current();
			/** @var ActiveRecordPermissionsTrait $modelObjectOrClass */
			return ($modelObjectOrClass::scope($this, $user));
		}

		return $this;
	}

}