<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
 * Class m210508_135006_RefOrderStatuses
 */
class m210508_135006_RefOrderStatuses extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('ref_merch_order_statuses', [
			'id' => $this->primaryKey(),
			'name' => $this->string(255)->notNull(),
			'color' => $this->string(255)->null(),
			'textcolor' => $this->string(255)->null(),
			'deleted' => $this->boolean()->notNull()->defaultValue(false)
		]);

		$this->createIndex('deleted', 'ref_merch_order_statuses', 'deleted');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('ref_merch_order_statuses');
	}

}
