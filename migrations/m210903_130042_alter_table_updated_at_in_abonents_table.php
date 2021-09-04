<?php
declare(strict_types = 1);

use app\components\db\Migration;

/**
* Class m210903_130042_alter_table_updated_at_in_abonents_table
*/
class m210903_130042_alter_table_updated_at_in_abonents_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn('abonents', 'updated_at', $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn('abonents', 'updated_at', $this->timestamp()->notNull());
	}

}
