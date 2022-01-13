<?php
declare(strict_types = 1);

namespace app\components\helpers;

/**
 * Класс помощи для работы с БД
 */
class DbHelper {
	// Максимальное значение типа INT для 13 версии
	public const MAX_INT_PGSQL13 = 2147483647;
}
