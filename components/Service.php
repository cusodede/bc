<?php
declare(strict_types = 1);

namespace app\components;

use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\Transaction;

/**
 * Class Service
 * @package app\models\core
 */
class Service extends Model {

	/**
	 * Сброс к заводским настройкам.
	 * Очистка всех таблиц с данными
	 * Очистка всех справочников.
	 * Всё в ноль.
	 * После очистки создаётся чистый административный аккаунт.
	 */
	public static function ResetDB():bool {
		$connection = Yii::$app->db;
		$transaction = new Transaction([
			'db' => $connection
		]);
		$transaction->begin();
		$tables = $connection->schema->tableNames;
		ArrayHelper::removeValue($tables, 'migration');
		$connection->createCommand("SET FOREIGN_KEY_CHECKS = 0;")->execute();
		try {
			foreach ($tables as $table) {
				$connection->createCommand("TRUNCATE TABLE $table")->execute();
				$connection->createCommand("ALTER TABLE $table AUTO_INCREMENT = 0")->execute();
			}
			$connection->createCommand("SET FOREIGN_KEY_CHECKS = 1;")->execute();
			$connection->createCommand(/** @lang MySQL */ "INSERT INTO sys_users (id, username, login, password, salt, email, comment, create_date, deleted) VALUES (1, 'admin', 'admin', 'admin', NULL, 'admin@localhost', 'Системный администратор', CURRENT_DATE(), 0)")->execute();
		} catch (Throwable) {
			$transaction->rollBack();
			return false;
		}

		$transaction->commit();
		return true;
	}
}