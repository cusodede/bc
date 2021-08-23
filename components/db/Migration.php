<?php
declare(strict_types = 1);

namespace app\components\db;

/**
 * Расширение функционала \yii\db\Migration
 */
class Migration extends  \yii\db\Migration
{
	/**
	 * Создать триггер для обновления поля updated_at при обновлении строки
	 * @param string $tableName
	 * @return bool
	 */
	public function createOnUpdateTrigger(string $tableName): bool
	{
		$trigger = <<< SQL
CREATE TRIGGER update_updated_at BEFORE UPDATE
    ON {$tableName} FOR EACH ROW EXECUTE PROCEDURE
    update_updated_at_column();
SQL;
		$this->execute($trigger);
		return 	(bool)$this->db->createCommand("select trigger_name from information_schema.triggers where event_object_table='{$tableName}' and trigger_name = 'update_updated_at'")->queryScalar();
	}
}