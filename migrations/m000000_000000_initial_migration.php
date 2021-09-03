<?php
declare(strict_types = 1);

use yii\db\Migration;

/**
 * Миграция, инициализирующая нужные в последующих вызовах функции (для pgsql)
 */
class m000000_000000_initial_migration extends Migration
{

	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		if ('pgsql' === $this->db->driverName) {
			$this->execute("CREATE OR REPLACE FUNCTION update_updated_at_column() RETURNS TRIGGER AS $$ BEGIN NEW.updated_at = now(); RETURN NEW; END; $$ language 'plpgsql'");
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		if ('pgsql' === $this->db->driverName) {
			$this->execute("DROP FUNCTION IF EXISTS update_updated_at_column();");
		}
	}

}
