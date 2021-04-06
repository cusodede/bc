<?php
declare(strict_types = 1);

namespace app\models\sys\permissions;

/**
 * Class Permissions
 */
final class Permissions extends PermissionsAR {
	/*Любое из перечисленных прав*/
	public const LOGIC_OR = 0;
	/*Все перечисленные права*/
	public const LOGIC_AND = 1;
	/*Ни одно из перечисленных прав*/
	public const LOGIC_NOT = 2;
}