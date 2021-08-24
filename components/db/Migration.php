<?php
declare(strict_types = 1);

namespace app\components\db;

use yii\db\Migration as VendorMigration;

/**
 * Расширение функционала \yii\db\Migration
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
//			if (false === (bool)$this->db->createCommand("select trigger_name from information_schema.triggers where event_object_table='{$tableName}' and trigger_name = 'update_updated_at'")->queryScalar()) {
//				throw new Exception("Не удалось создать триггер для таблицы {$tableName}");
//			}
		}
	}

	public function update_updated_at_column(): void
	{
		if ('pgsql' === $this->db->driverName) {
			$this->execute("CREATE OR REPLACE FUNCTION update_updated_at_column() RETURNS TRIGGER AS $$ 
BEGIN
    NEW.updated_at = now();
    RETURN NEW;
END;
$$ language 'plpgsql'");
		}
	}

}