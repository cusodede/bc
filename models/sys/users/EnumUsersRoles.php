<?php
declare(strict_types = 1);

namespace app\models\sys\users;

use app\models\common\EnumTrait;

/**
 * Class EnumUsersRoles
 * @package app\models\sys\users
 */
class EnumUsersRoles
{
	use EnumTrait;

	public const ADMIN = 'admin';
	public const BEELINE_MANAGER = 'beeline_manager';
	public const PARTNER_MANAGER = 'partner_manager';

	/**
	 * {@inheritdoc}
	 */
	public static function mapData(): array
	{
		return [
			self::ADMIN => 'Администратор',
			self::BEELINE_MANAGER => 'Сотрудник Beeline',
			self::PARTNER_MANAGER => 'Сотрудник партнёра',
		];
	}
}