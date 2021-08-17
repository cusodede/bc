<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210817_143341_update_restore_code_type_in_users_table
*/
class m210817_143341_update_restore_code_type_in_users_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn('sys_users', 'restore_code', $this->string(255));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn('sys_users', 'restore_code', $this->string(40));
	}

}
