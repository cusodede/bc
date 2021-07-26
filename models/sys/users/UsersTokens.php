<?php
declare(strict_types = 1);

namespace app\models\sys\users;

use app\components\db\ActiveRecordTrait;
use app\models\sys\users\active_record\UsersTokens as ActiveRecordUsersTokens;
use app\modules\api\tokenizers\RefreshTokenType;
use cusodede\jwt\JwtHttpBearerAuth;
use Exception;
use pozitronik\helpers\DateHelper;
use yii\behaviors\TimestampBehavior;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\HttpHeaderAuth;
use yii\helpers\ArrayHelper;

/**
 * Class UsersTokens
 * @package app\models\sys\users
 *
 * @property UsersTokens|null $relatedParentToken
 * @property UsersTokens[] $relatedChildTokens
 * @property-read UsersTokens|null $relatedMainParentToken
 */
class UsersTokens extends ActiveRecordUsersTokens {
	use ActiveRecordTrait;

	/**
	 * Массив соответствий между методом авторизации и типом токена.
	 * @see Users::findIdentityByAccessToken()
	 */
	public const TOKEN_TYPES = [
		HttpHeaderAuth::class => 1,
		HttpBearerAuth::class => 2,
		JwtHttpBearerAuth::class => 3,
		RefreshTokenType::class => 4
	];

	/**
	 * {@inheritdoc}
	 */
	public function behaviors():array {
		return ArrayHelper::merge(parent::behaviors(), [
			[
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'created',
				'updatedAtAttribute' => false,
				'value' => static function($event) {
					return DateHelper::from_unix_timestamp(time());
				}
			]
		]);
	}

	/**
	 * Получение самого верхнего родительского токена.
	 * @return self
	 */
	public function getRelatedMainParentToken():self {
		$token = $this;
		while (null !== $parentToken = $token->relatedParentToken) {
			$token = $parentToken;
		}

		return $token;
	}

	/**
	 * Проверяем актуальность ключа доступа.
	 * @return bool
	 */
	public function isValid():bool {
		return (null === $this->valid) || DateHelper::unix_timestamp($this->valid) > time();
	}

	/**
	 * @param null|string $type
	 * @return int|null
	 * @throws Exception
	 */
	public static function getIdByType(?string $type):?int {
		return ArrayHelper::getValue(self::TOKEN_TYPES, $type);
	}
}