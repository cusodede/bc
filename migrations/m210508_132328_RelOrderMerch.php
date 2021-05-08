<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
 * Class m210508_132328_RelOrderMerch
 */
class m210508_132328_RelOrderMerch extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('relation_order_to_merch', [
			'id' => $this->primaryKey(),
			'order_id' => $this->integer()->notNull(),
			'merch_id' => $this->integer()->notNull(),
		]);

		$this->createIndex('order_id_merch_id', 'relation_order_to_merch', ['order_id', 'merch_id'], true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('relation_order_to_merch');
	}

}
