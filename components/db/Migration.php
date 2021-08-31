<?php
declare(strict_types = 1);

namespace app\components\db;

use yii\db\Migration as VendorMigration;

/**
 * Расширение функционала \yii\db\Migration для поддержки pgsql
 */
class Migration extends VendorMigration
{
	/**
	 * Создать триггер для обновления поля updated_at при обновлении строки
	 * @param string $tableName
	 * @return void
	 */
	public function createOnUpdateTrigger(string $tableName): void
	{
		if ('pgsql' === $this->db->driverName) {
			$this->execute("CREATE TRIGGER update_updated_at  BEFORE UPDATE ON {$tableName} FOR EACH ROW EXECUTE PROCEDURE update_updated_at_column()");
		}
	}
}