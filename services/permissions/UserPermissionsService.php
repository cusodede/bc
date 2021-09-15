<?php
declare(strict_types = 1);

namespace app\services\permissions;

use app\models\sys\permissions\Permissions;
use app\models\sys\users\Users;
use Throwable;
use app\models\sys\users\EnumUsersRoles;
use yii\helpers\ArrayHelper;

/**
 * Вспомогательный сервис для пермиссий пользователей.
 * Содержит сервисную логику пермиссий (ролей) пользователей.
 * Создан, чтобы разгрузить модели.
 */
class UserPermissionsService
{
	private Users $user;

	/**
	 * @param Users $user
	 */
	public function __construct(Users $user)
	{
		$this->user = $user;
	}

	/**
	 * Перезагружает главную пермиссию пользователя (может быть только одна).
	 * Возвращает массив ВСЕХ пермиссий пользователя.
	 * @param string|null $namePermission
	 * @return array
	 */
	public function resetMainPermission(?string $namePermission): array
	{
		$permissions = ArrayHelper::getColumn($this->user->relatedPermissions, 'id');
		$permissions = array_filter(
			$permissions,
			fn(int $value): bool => !in_array($value, $this->getMainUserPermissionsIds(), true)
		);
		if (null !== ($role = Permissions::findByName($namePermission))) {
			$permissions[] = $role->id;
		}
		return $permissions;
	}

	/**
	 * Возвращает главную пермиссию пользователя, используется на фронте.
	 * @return string|null
	 * @throws Throwable
	 */
	public function getMainPermission(): ?string
	{
		if ($this->user->hasPermission([EnumUsersRoles::ADMIN])) {
			return EnumUsersRoles::ADMIN;
		}
		if ($this->user->hasPermission([EnumUsersRoles::BEELINE_MANAGER])) {
			return EnumUsersRoles::BEELINE_MANAGER;
		}
		return $this->user->hasPermission([EnumUsersRoles::PARTNER_MANAGER]) ? EnumUsersRoles::PARTNER_MANAGER : null;
	}

	/**
	 * Возвращает массив идентификаторов, главных пермиссий (ролей) пользователей.
	 * @return array
	 */
	public function getMainUserPermissionsIds(): array
	{
		return Permissions::find()->select('id')->where(['name' => array_keys(EnumUsersRoles::mapData())])->column();
	}
}