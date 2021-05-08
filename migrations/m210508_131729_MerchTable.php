<?php
declare(strict_types = 1);
use yii\db\Migration;

/**
 * Class m210508_130802_MerchOrderTable
 */
class m210508_131729_MerchTable extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable('merch', [
			'id' => $this->primaryKey(),
			'name' => $this->integer()->notNull()->comment('Название товара'),
			'item_class' => $this->string()->notNull()->comment('Класс товара'),
			'create_date' => $this->dateTime()->notNull()->comment('Дата регистрации'),
			'deleted' => $this->boolean()->notNull()->defaultValue(0)
		]);

		$this->createIndex('deleted', 'merch', 'deleted');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable('merch');
	}

}
