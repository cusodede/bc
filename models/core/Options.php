<?php
declare(strict_types = 1);

namespace app\models\core;

use pozitronik\helpers\ArrayHelper;
use pozitronik\sys_options\models\SysOptions;
use Throwable;

/**
 * Class Options
 * Перечисление системных опций, вынесенных в SysOptions
 */
class Options
{
	/*значения по умолчанию*/
	public const ASSETS_PUBLISHOPTIONS_FORCECOPY = 'ASSETS_PUBLISHOPTIONS_FORCECOPY';
	public const GRANT_TYPE_ISSUE_IGNORE_TOKEN_VALIDATION = 'GRANT_TYPE_ISSUE_IGNORE_TOKEN_VALIDATION';

	/**
	 * Формат: ключ => значение по умолчанию
	 */
	public const DEFAULT_VALUES = [
		self::ASSETS_PUBLISHOPTIONS_FORCECOPY => false,
		self::GRANT_TYPE_ISSUE_IGNORE_TOKEN_VALIDATION => false,
	];

	/**
	 * Формат: ключ => описание опции
	 */
	public const OPTIONS_LABELS = [
		self::ASSETS_PUBLISHOPTIONS_FORCECOPY => 'Отключить кеширование ассетов',
		self::GRANT_TYPE_ISSUE_IGNORE_TOKEN_VALIDATION => 'Игнорировать валидацию токенов в GrantTypeIssue::validate()',
	];

	/**
	 * Получить значение опции (если не задана - то значение по умолчанию, если не существует - то null).
	 * @param string $key
	 * @return mixed
	 * @throws Throwable
	 */
	public static function getValue(string $key)
	{
		return SysOptions::getStatic($key, ArrayHelper::getValue(self::DEFAULT_VALUES, $key));
	}
}