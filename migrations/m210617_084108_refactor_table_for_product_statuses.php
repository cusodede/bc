<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210617_084108_refactor_table_for_product_statuses
*/
class m210617_084108_refactor_table_for_product_statuses extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropColumn('product_statuses', 'start_date');
		$this->renameColumn('product_statuses', 'end_date', 'expire_date');
		$this->alterColumn('product_statuses', 'expire_date', $this->dateTime()->notNull());
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->renameColumn('product_statuses', 'expire_date', 'end_date');
		$this->addColumn('product_statuses', 'start_date', $this->dateTime());
	}
}
