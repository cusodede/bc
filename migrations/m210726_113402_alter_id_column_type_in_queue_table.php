<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210726_113402_alter_id_column_type_in_queue_table
*/
class m210726_113402_alter_id_column_type_in_queue_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn('queue', 'id', $this->char(36)->notNull());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn('queue', 'id', $this->integer());
	}

}
