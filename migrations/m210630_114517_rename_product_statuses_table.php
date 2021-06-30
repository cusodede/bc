<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
* Class m210630_114517_rename_product_statuses_table
*/
class m210630_114517_rename_product_statuses_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->renameTable('product_statuses', 'products_journal');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->renameTable('products_journal', 'product_statuses');
	}
}
