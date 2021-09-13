<?php
declare(strict_types = 1);

namespace app\components\db;

use yii\db\Migration as VendorMigration;

/**
 * Расширение функционала \yii\db\Migration для поддержки pgsql и прочего такого
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
			$this->execute("CREATE TRIGGER update_updated_at BEFORE UPDATE ON {$tableName} FOR EACH ROW EXECUTE PROCEDURE update_updated_at_column()");
		}
	}

	/**
	 * Переопределяем имя индекса, чтобы оно  вмещалось в 64 символа всегда
	 * Добавляем к имени индекса имя таблицы (если не добавлено вручную) для обеспечения уникальности имён индексов
	 * в глобальном неймспейсе
	 * @param string $name
	 * @param string $table
	 * @return string
	 */
	private function checkIndexName(string $name, string $table): string
	{
		$table = $this->db->schema->unquoteSimpleTableName(mb_strtolower($this->db->quoteSql($table)));//на случай прилёта имени таблицы в префиксном формате {{%table_name}}
		$name  = mb_strtolower($name);
		if (0 !== substr_compare($name, $table, 0, strlen($table))) {//имя индекса не содержит имени таблицы
			$name = "{$table}_{$name}";
		}

		if (strlen($name) > 64 /*max index name length*/) {
			$name = substr($name, -64, 64);
		}
		return $name;
	}

	/**
	 * @inheritDoc
	 */
	public function createIndex($name, $table, $columns, $unique = false): void
	{
		parent::createIndex($this->checkIndexName($name, $table), $table, $columns, $unique);
	}

	/**
	 * @inheritDoc
	 */
	public function dropIndex($name, $table): void
	{
		parent::dropIndex($this->checkIndexName($name, $table), $table);
	}
}