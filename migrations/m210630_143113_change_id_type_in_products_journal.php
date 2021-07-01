<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210630_143113_change_id_type_in_products_journal
*/
class m210630_143113_change_id_type_in_products_journal extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn('products_journal', 'id', $this->char(36)->notNull());
		$this->alterColumn('billing_journal', 'id', $this->char(36)->notNull());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->alterColumn('billing_journal', 'id', $this->string(36)->notNull());
		$this->alterColumn('products_journal', 'id', $this->integer()->notNull());
	}

}
