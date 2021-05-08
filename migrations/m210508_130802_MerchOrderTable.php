<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
 * Class m210508_130802_MerchOrderTable
 */
class m210508_130802_MerchOrderTable extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('merch_order', [
			'id' => $this->primaryKey(),
			'initiator' => $this->integer()->notNull()->comment('Заказчик'),
			'store' => $this->integer()->notNull()->comment('Магазин'),
			'create_date' => $this->dateTime()->notNull()->comment('Дата регистрации'),
			'deleted' => $this->boolean()->notNull()->defaultValue(0)
		]);

		$this->createIndex('initiator', 'merch_order', 'initiator');
		$this->createIndex('store', 'merch_order', 'store');
		$this->createIndex('deleted', 'merch_order', 'deleted');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('merch_order');
	}

}
