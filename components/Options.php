<?php
declare(strict_types = 1);

namespace app\components;

use app\components\helpers\ArrayHelper;
use pozitronik\sys_options\models\SysOptions;
use Throwable;

/**
 * Class Options
 * Перечисление системных опций, вынесенных в SysOptions
 */
class Options {
	/*значения по умолчанию*/
	public const ASSETS_PUBLISHOPTIONS_FORCECOPY = 'ASSETS_PUBLISHOPTIONS_FORCECOPY';
	public const GRANT_TYPE_ISSUE_IGNORE_TOKEN_VALIDATION = 'GRANT_TYPE_ISSUE_IGNORE_TOKEN_VALIDATION';
	public const GRAPHQL_IGNORE_TOKEN_VALIDATION = 'GRAPHQL_IGNORE_TOKEN_VALIDATION';
	public const AJAX_MODALS_ENABLED = 'AJAX_MODALS_ENABLED';
	public const SCOPE_IGNORE_ENABLE = 'SCOPE_IGNORE_ENABLE';
	public const ENABLE_SQL_DEBUG_TRACE = 'ENABLE_SQL_DEBUG_TRACE';
	public const ENABLE_INTERNAL_HISTORY = 'ENABLE_INTERNAL_HISTORY';

	/**
	 * Формат: ключ => значение по умолчанию
	 */
	public const DEFAULT_VALUES = [
		self::ASSETS_PUBLISHOPTIONS_FORCECOPY => false,
		self::GRANT_TYPE_ISSUE_IGNORE_TOKEN_VALIDATION => false,
		self::GRAPHQL_IGNORE_TOKEN_VALIDATION => false,
		self::AJAX_MODALS_ENABLED => true,
		self::SCOPE_IGNORE_ENABLE => false,
		self::ENABLE_SQL_DEBUG_TRACE => false,
		self::ENABLE_INTERNAL_HISTORY => true
	];

	/**
	 * Формат: ключ => описание опции
	 */
	public const OPTIONS_LABELS = [
		self::ASSETS_PUBLISHOPTIONS_FORCECOPY => 'Отключить кеширование ассетов',
		self::GRANT_TYPE_ISSUE_IGNORE_TOKEN_VALIDATION => 'Игнорировать валидацию токенов в GrantTypeIssue::validate()',
		self::GRAPHQL_IGNORE_TOKEN_VALIDATION => 'Отключить JWT-авторизацию для GQL API (авторизовать по ID пользователя)',
		self::AJAX_MODALS_ENABLED => 'Открывать ссылки в модальных окнах',
		self::SCOPE_IGNORE_ENABLE => 'Отключить проверку областей видимости данных для всех пользователей',
		self::ENABLE_SQL_DEBUG_TRACE => 'Добавлять в выполняемые запросы отладочную информацию',
		self::ENABLE_INTERNAL_HISTORY => 'Включить запись всех изменений через HistoryBehavior'
	];

	/**
	 * Получить значение опции (если не задана - то значение по умолчанию, если не существует - то null).
	 * @param string $key
	 * @return mixed
	 * @throws Throwable
	 */
	public static function getValue(string $key) {
		return SysOptions::getStatic($key, ArrayHelper::getValue(self::DEFAULT_VALUES, $key));
	}

	/**
	 * Все логические настройки
	 * @return bool[]
	 * @throws Throwable
	 */
	public static function boolOptions():array {
		return [
			self::ASSETS_PUBLISHOPTIONS_FORCECOPY => self::getValue(self::ASSETS_PUBLISHOPTIONS_FORCECOPY),
			self::GRANT_TYPE_ISSUE_IGNORE_TOKEN_VALIDATION => self::getValue(self::GRANT_TYPE_ISSUE_IGNORE_TOKEN_VALIDATION),
			self::GRAPHQL_IGNORE_TOKEN_VALIDATION => self::getValue(self::GRAPHQL_IGNORE_TOKEN_VALIDATION),
			self::AJAX_MODALS_ENABLED => self::getValue(self::AJAX_MODALS_ENABLED),
			self::SCOPE_IGNORE_ENABLE => self::getValue(self::SCOPE_IGNORE_ENABLE),
			self::ENABLE_SQL_DEBUG_TRACE => static::getValue(self::ENABLE_SQL_DEBUG_TRACE),
			self::ENABLE_INTERNAL_HISTORY => static::getValue(self::ENABLE_INTERNAL_HISTORY)
		];
	}

}